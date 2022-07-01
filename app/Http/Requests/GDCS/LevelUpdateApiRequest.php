<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelUpdateApiRequest extends Request
{
    public function rules(): array
    {
        $levelID = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'desc' => [
                'required',
                'string',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'audio_track' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'song_id' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'password' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'requested_stars' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($levelID)
            ],
            'unlisted' => [
                'required',
                'boolean',
                Rule::unique(Level::class)->ignore($levelID)
            ]
        ];
    }
}
