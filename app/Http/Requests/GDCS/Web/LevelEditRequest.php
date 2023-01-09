<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class LevelEditRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'desc' => [
                'required',
                'string'
            ],
            'password' => [
                'required',
                'integer',
                'between:0,999999'
            ],
            'audio_track' => [
                'required',
                'integer',
                'between:-1,37'
            ],
            'song_id' => [
                'required',
                'integer'
            ],
            'unlisted' => [
                'required',
                'boolean'
            ]
        ];
    }
}
