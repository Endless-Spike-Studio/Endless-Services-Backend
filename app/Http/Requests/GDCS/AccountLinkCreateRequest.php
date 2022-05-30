<?php

namespace App\Http\Requests\GDCS;

use App\Enums\GDCS\GeometryDashServer;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AccountLinkCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'server' => [
                'required',
                'string',
                'active_url',
                Rule::in(
                    collect(
                        GeometryDashServer::cases()
                    )->pluck('value')
                )
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
