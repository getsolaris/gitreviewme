<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @var User $user
     */
    protected User $user;

    /**
     * UserController constructor.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * User Profile.
     *
     * @OA\Get(
     *      path="/api/users",
     *      tags={"Users"},
     *      summary="User Profile",
     *      operationId="users_me",
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
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection($this->user->all());
    }

    /**
     * @OA\Get(
     *      path="/api/auth/me",
     *      tags={"Users"},
     *      summary="Show Current User",
     *      operationId="show_current_user",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
     *  )
     *
     * @return UserResource|JsonResponse
     */
    public function me(): UserResource|JsonResponse
    {
        try {
            return new UserResource(auth()->user());
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/users/{user_id}",
     *      tags={"Users"},
     *      summary="Show User",
     *      operationId="show_user",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
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
     * @return UserResource|JsonResponse
     */
    public function show(int $id): UserResource|JsonResponse
    {
        try {
            return new UserResource($this->user->findOrFail($id));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update a User.
     *
     * @OA\Put(
     *      path="/api/users",
     *      tags={"Users"},
     *      summary="User Update",
     *      operationId="users_edit",
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      },
     *
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          @OA\Schema(
     *               type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
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
     * @param UpdateRequest $request
     * @return UserResource
     */
    public function update(UpdateRequest $request): UserResource
    {
        $user = $this->user->findOrFail(auth()->user()->id);
        $user->update($request->validated());

        return new UserResource($user);
    }
}
