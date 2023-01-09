<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class AccountPasswordChangeRequest extends Request
{
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'confirmed'
            ]
        ];
    }
}
