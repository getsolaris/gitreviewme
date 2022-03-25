<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GithubRepositoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'oauth_provider_id' => $this->oauth_provider_id,
            'repository_id' => $this->repository_id,
            'name' => $this->name,
            'description' => $this->description,
            'full_name' => $this->full_name,
            'node_id' => $this->node_id,
            'url' => $this->url,
            'data' => $this->data,
            'owner' => $this->owner,
            'hide' => $this->hide,
            'license' => $this->license,
            'topics' => $this->topics,
            'stargazers_count' => $this->stargazers_count,
            'watchers_count' => $this->watchers_count,
            'forks_count' => $this->forks_count,
            'open_issues' => $this->open_issues,
            'default_branch' => $this->default_branch,
            'language' => $this->language,
            'is_visibility' => $this->is_visibility,
            'has_project' => $this->has_project,
            'repository_created_at' => $this->repository_created_at,
            'repository_updated_at' => $this->repository_updated_at,
            'repository_pushed_at' => $this->repository_pushed_at,
        ];
    }
}
