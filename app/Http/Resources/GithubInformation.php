<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GithubInformation extends JsonResource
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
            'name' => $this->name,
            'company' => $this->company,
            'blog' => $this->blog,
            'location' => $this->location,
            'email' => $this->email,
            'bio' => $this->bio,
            'followers' => $this->followers,
            'following' => $this->following,
            'user_registered_at' => $this->user_registered_at,
            'user_updated_at' => $this->user_updated_at,
        ];
    }
}
