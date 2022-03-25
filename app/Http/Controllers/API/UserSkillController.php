<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserSkillResource;
use App\Models\Skill;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\SkillService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserSkillController extends Controller
{
    /**
     * @var User $user
     */
    protected User $user;

    /**
     * @var Skill $skill
     */
    protected Skill $skill;

    /**
     * @var SkillService $skillService
     */
    protected SkillService $skillService;

    /**
     * UserSkillController constructor.
     * @param User $user
     * @param Skill $skill
     * @param SkillService $skillService
     */
    public function __construct(User $user, Skill $skill, SkillService $skillService)
    {
        $this->user = $user;
        $this->skill = $skill;
        $this->skillService = $skillService;
    }

    /**
     * @OA\Get(
     *      path="/api/users/{user_id}/skills",
     *      tags={"User Skills"},
     *      summary="User Skills",
     *      operationId="user_skills",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      },
     *
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
     * @return UserSkillResource
     */
    public function index(int $id): UserSkillResource
    {
        return new UserSkillResource($this->user->findOrFail($id));
    }

    /**
     * @OA\Get(
     *      path="/api/users/{user_id}/skills/{skill_id}",
     *      tags={"User Skills"},
     *      summary="User Skill Show",
     *      operationId="user_skills_show",
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      },
     *
     *      @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="skill_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
     *  )
     *
     * @param int $id
     * @param int $skillId
     * @return mixed
     */
    public function show(int $id, int $skillId): mixed
    {
        return $this->user->findOrFail($id)->skills->find($skillId);
    }

    /**
     * @OA\Post(
     *      path="/api/users/{user_id}/skills",
     *      tags={"User Skills"},
     *      summary="User Skill Store",
     *      operationId="user_skill_store",
     *      security={
     *          {
     *              "bearerAuth": {}
     *          }
     *      },
     *
     *      @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="skills[]",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="array", @OA\Items(type="string")),
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
     * @param int $id
     * @param Request $request
     * @return Collection|array
     */
    public function store(int $id, Request $request): Collection|array
    {
        return $this->skillService->create($this->user->find($id), $request->skills);
    }

    /**
     * @param int $id
     * @param int $skillId
     * @return int
     */
    public function destroy(int $id, int $skillId): int
    {
        return $this->skillService->destroy($this->user->find($id), $skillId);
    }
}
