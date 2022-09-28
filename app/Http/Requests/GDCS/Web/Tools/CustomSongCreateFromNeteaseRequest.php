<?php

namespace App\Http\Requests\GDCS\Web\Tools;

use App\Http\Requests\Request;

class CustomSongCreateFromNeteaseRequest extends Request
{
    public function rules(): array
    {
        return [
            'music_id' => [
                'required',
                'integer'
            ]
        ];
    }
}
