<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;

class AccountLinkCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'server' => [
                'required',
                'string',
                'active_url'
            ],
            'name' => [
                'required',
                'string'
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }
}
