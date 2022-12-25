<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::exists(Account::class)
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }
}
