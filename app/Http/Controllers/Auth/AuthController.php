<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Login a User.
     *
     * @OA\Post(
     *      path="/api/auth/login",
     *      tags={"Authentication"},
     *      summary="Login",
     *      operationId="login",
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (empty (User::whereEmail($request->email)->first()->password)) {
            return response()->json(['error' => 'OAuth Login'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->token($token);
    }

    /**
     * Register a User.
     *
     * @OA\Post(
     *      path="/api/auth/register",
     *      tags={"Authentication"},
     *      summary="Register",
     *      operationId="register",
     *
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     *
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $request->validated();
        $request->merge([
            'role' => 0,
            'password' => $request->password,
        ]);

        $user = User::create($request->all());

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @OA\Post(
     *      path="/api/auth/logout",
     *      tags={"Authentication"},
     *      summary="Logout",
     *      operationId="logout",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      }
     *  )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @OA\Post(
     *      path="/api/auth/refresh",
     *      tags={"Authentication"},
     *      summary="Refresh",
     *      operationId="refresh",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      }
     *  )
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->token(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function token(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
