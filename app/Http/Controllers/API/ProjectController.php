<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Resources\ProjectResource;
use App\Models\GithubRepository;
use App\Models\OAuthProvider;
use App\Models\Project;
use App\Models\User;
use App\Services\GithubService;
use App\Services\SkillService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * @var Project $project
     */
    protected Project $project;

    /**
     * @var SkillService $skillService
     */
    protected SkillService $skillService;

    /**
     * @var GithubService $githubService
     */
    protected GithubService $githubService;

    /**
     * ProjectController constructor.
     * @param Project $project
     * @param SkillService $skillService
     * @param GithubService $githubService
     */
    public function __construct(Project $project, SkillService $skillService, GithubService $githubService) {
        $this->project = $project;
        $this->skillService = $skillService;
        $this->githubService = $githubService;
    }

    /**
     * @OA\Get(
     *      path="/api/projects",
     *      tags={"Projects"},
     *      summary="Projects",
     *      operationId="projects",
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
        $projects = $this->project->all();
        $projects->load('user');

        return ProjectResource::collection($projects);
    }

    /**
     * TODO: id 를 uuid or decode
     *
     * @OA\Get(
     *      path="/api/projects/{project_id}",
     *      tags={"Projects"},
     *      summary="Projects Show",
     *      operationId="projects_show",
     *
     *      @OA\Parameter(
     *          name="project_id",
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
     * @return ProjectResource
     */
    public function show(int $id): ProjectResource
    {
        $projects = $this->project->findOrFail($id);
        $projects->load('user');

        return new ProjectResource($projects);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request) {
        $user = User::find($request->user_id);
        $oauthProvider = OAuthProvider::whereUserId($user->id)->first();
        $repository = GithubRepository::whereOauthProviderId($request->oauth_provider_id)
            ->whereId($request->repository_id)->firstOrFail();

        if (! $repository) {
            return 'a'; // TODO: complete
        }

        if (Project::whereUserId($user->id)->whereRepositoryId($repository->id)->exists()) {
            return 'b'; // TODO: complete
        }

        $readme = $this->githubService->readme($oauthProvider->provider_user_handle_id, $repository->name);

        return $user->projects()->create([
            'repository_id' => $repository->id,
            'title' => $repository->name,
            'description' => $repository->description,
            'content' => base64_decode($readme['content']),
            'base64_content' => $readme['content'],
            'url' => $repository->url,
            'language' => $repository->language,
            'license' => $repository->license,
        ]);
    }
}
