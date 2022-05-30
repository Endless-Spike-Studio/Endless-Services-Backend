<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique(User::class)
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique(User::class)
            ],
            'password' => [
                'required',
                'string',
                'confirmed'
            ]
        ];
    }
}
