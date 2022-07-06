<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;

class AccountPermissionUpdateApiRequest extends Request
{
    public function rules(): array
    {
        return [
            'abilities' => [
                'nullable',
                'array',
            ],
            'roles' => [
                'nullable',
                'array',
            ],
        ];
    }
}
