<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;

class CustomSongNeteaseCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'music_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
