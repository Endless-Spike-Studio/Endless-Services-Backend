<?php

namespace App\Http\Requests\GDCN;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserLoginApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::exists(User::class),
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }
}
