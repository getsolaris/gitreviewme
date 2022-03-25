<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'repository_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'oauth_provider_id' => ['required', 'integer'],
        ];
    }
}
