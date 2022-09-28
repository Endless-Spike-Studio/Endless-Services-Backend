<?php

namespace App\Http\Requests\GDCS\Web\Tools;

use App\Http\Requests\Request;

class CustomSongCreateFromLinkRequest extends Request
{
    public function rules(): array
    {
        return [
            'link' => [
                'required',
                'string',
                'active_url'
            ],
            'name' => [
                'required',
                'string'
            ],
            'artist_name' => [
                'required',
                'string'
            ]
        ];
    }
}
