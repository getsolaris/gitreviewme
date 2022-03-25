<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OAuthProviderResource
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string $provider_user_handle_id
 * @property string $provider_user_avatar_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @package App\Http\Resources
 */
class OAuthProviderResource extends JsonResource
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
            'provider' => $this->provider,
            'provider_user_id' => $this->provider_user_id,
            'provider_user_handle_id' => $this->provider_user_handle_id,
            'provider_user_avatar_url' => $this->provider_user_avatar_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'github_information' => new GithubInformation($this->githubInformation),
            'github_repositories' => GithubRepositoryResource::collection($this->whenLoaded('githubRepositories')),
        ];
    }
}
