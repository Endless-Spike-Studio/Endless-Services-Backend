<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class AccountLinkToolCreateRequest extends Request
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
