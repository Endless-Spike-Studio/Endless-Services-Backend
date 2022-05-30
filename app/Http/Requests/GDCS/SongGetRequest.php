<?php

namespace App\Http\Requests\GDCS;

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
