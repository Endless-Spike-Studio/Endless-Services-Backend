<?php

namespace App\Http\Requests\GDCS\Web\Tools;

use App\Http\Requests\Request;

class RemoteLevelLoadRequest extends Request
{
    public function rules(): array
    {
        return [
            'page' => [
                'integer'
            ]
        ];
    }
}
