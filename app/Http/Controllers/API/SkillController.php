<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillController extends Controller
{
    /**
     * @var Skill $skill
     */
    protected Skill $skill;

    /**
     * SkillController constructor.
     * @param Skill $skill
     */
    public function __construct(Skill $skill)
    {
        $this->skill = $skill;
    }

    /**
     * @OA\Get(
     *      path="/api/skills",
     *      tags={"Skills"},
     *      summary="Skills",
     *      operationId="skills",
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
        return SkillResource::collection($this->skill->all());
    }

    /**
     * @OA\Get(
     *      path="/api/skills/{skill_id}",
     *      tags={"Skills"},
     *      summary="Skills Show",
     *      operationId="skill_show",
     *
     *      @OA\Parameter(
     *          name="skill_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *      ),
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
     * @param int $id
     * @return SkillResource
     */
    public function show(int $id): SkillResource
    {
        return new SkillResource($this->skill->find($id));
    }
}
