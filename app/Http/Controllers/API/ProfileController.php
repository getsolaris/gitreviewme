<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     *
     * @OA\Get(
     *      path="/api/profiles",
     *      tags={"Profiles"},
     *      summary="Profiles",
     *      operationId="profiles",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth" => {}
     *          }
     *      }
     *  )
     *
     * @param User $user
     * @return UserResource
     */
    public function index(User $user): UserResource
    {
        $user = $user->find(auth()->user()->id);
        $user->load(['skills', 'projects', 'oauthProvider.githubRepositories']);

        return new UserResource($user);
    }

    /**
     *
     ** @OA\Get(
     *      path="/api/profiles/{user_id}",
     *      tags={"Profiles"},
     *      summary="Profile User",
     *      operationId="show_profile_user",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth" => {}
     *          }
     *      },
     *      @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
     *  )
     *
     * @param int $id
     * @param User $user
     * @return UserResource
     */
    public function show(int $id, User $user): UserResource
    {
        $user = $user->findOrFail($id);
        $user->load(['skills', 'projects']);

        return new UserResource($user);
    }

    /**
     * @param string $handle
     * @param OAuthProvider $oauthProvider
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function showUserProviderHandleId(string $handle, OAuthProvider $oauthProvider): UserResource|\Illuminate\Http\JsonResponse
    {
        $oauthProvider = $oauthProvider->whereProviderUserHandleId($handle)->first();

        // TODO
        if (! $oauthProvider) {
            return response()->json([
                'aaa' => 'a'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = $oauthProvider->user;
        $user->load(['skills', 'projects', 'oauthProvider.githubRepositories']);

        return new UserResource($user);
    }
}
