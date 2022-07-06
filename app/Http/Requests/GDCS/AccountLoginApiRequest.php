<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountLoginApiRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::exists(Account::class),
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }
}
