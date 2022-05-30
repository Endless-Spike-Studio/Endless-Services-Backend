<?php

namespace App\Http\Requests\NGProxy;

use App\Http\Requests\Request;

class SongGetRequest extends Request
{
    public function rules(): array
    {
        return [
            'songID' => [
                'required',
                'integer'
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7'
            ]
        ];
    }
}
