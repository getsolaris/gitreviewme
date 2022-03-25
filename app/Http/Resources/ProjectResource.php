<?php

namespace App\Http\Resources;

use App\Models\GithubRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'view' => $this->view,
            'skills' => SkillResource::collection($this->skills),
            'user' => new UserResource($this->whenLoaded('user')),
            'github_repository' => new GithubRepositoryResource($this->githubRepository),
        ];
    }
}
