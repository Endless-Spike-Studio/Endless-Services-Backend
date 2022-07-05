<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class AccountPermissionUpdateApiRequest extends Request
{
    public function rules(): array
    {
        return [
            'abilities' => [
                'nullable',
                'array'
            ],
            'roles' => [
                'nullable',
                'array'
            ]
        ];
    }
}
