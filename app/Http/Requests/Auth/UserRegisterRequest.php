<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'between: 2, 100'],
            'email' => [
                'required',
                'string',
                'email',
                'max: 100',
                Rule::unique('users'),
            ],
            'password' => ['required', 'string', 'confirmed', 'min: 6'],
        ];
    }
}
