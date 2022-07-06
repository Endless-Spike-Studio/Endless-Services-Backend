<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;

class RoleUpdateApiRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string'
            ]
        ];
    }
}
