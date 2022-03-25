<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\OAuthProviderResource;
use App\Jobs\Github\SyncUserInformation;
use App\Jobs\Github\SyncUserRepositories;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    public function index(): AnonymousResourceCollection
    {
        return OAuthProviderResource::collection(OAuthProvider::all());
    }

    /**
     * @param string $provider
     * @return JsonResponse
     */
    public function redirect(string $provider): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver($provider)
                ->stateless()
                ->scopes(['user', 'repo'])
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    /**
     * @param string $provider
     * @return Application|Factory|View
     */
    public function handleCallback(string $provider): Application|Factory|View
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $user = $this->findOrCreateUser($provider, $user);

        $this->guard()->setToken(
            $token = $this->guard()->login($user)
        );

        return view('oauth/callback', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
            'user' => auth()->user(),
        ]);
    }

    /**
     * @param string $provider
     * @param SocialiteUser $socialiteUser
     * @return User
     */
    protected function findOrCreateUser(string $provider, SocialiteUser $socialiteUser): User
    {
        $oauthProvider = OAuthProvider::whereProvider($provider)
            ->whereProviderUserId($socialiteUser->getId())
            ->first();

        if ($oauthProvider) {
            $oauthProvider->update([
                'provider_user_handle_id' => $socialiteUser->getNickname(),
                'provider_user_avatar_url' => $socialiteUser->getAvatar(),
                'access_token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
            ]);

            SyncUserInformation::dispatch($oauthProvider);
            SyncUserRepositories::dispatch($oauthProvider);

            return $oauthProvider->user;
        }

        return $this->createUser($provider, $socialiteUser);
    }

    /**
     * @param string $provider
     * @param SocialiteUser $socialiteUser
     * @return User
     */
    protected function createUser(string $provider, SocialiteUser $socialiteUser): User
    {
        $user = User::create([
            'name' => $socialiteUser->getName() ?: $socialiteUser->getNickname(),
            'email' => $socialiteUser->getEmail(),
            'email_verified_at' => now(),
            'role' => 0,
        ]);

        $user->oauthProvider()->create([
            'provider' => $provider,
            'provider_user_id' => $socialiteUser->getId(),
            'provider_user_handle_id' => $socialiteUser->getNickname(),
            'provider_user_avatar_url' => $socialiteUser->getAvatar(),
            'access_token' => $socialiteUser->token,
            'refresh_token' => $socialiteUser->refreshToken,
        ]);

        SyncUserInformation::dispatch($user->oauthProvider);
        SyncUserRepositories::dispatch($user->oauthProvider);

        return $user;
    }
}
