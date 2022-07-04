<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelUpdateApiRequest extends Request
{
    public function rules(): array
    {
        $level = $this->route('level');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique(Level::class)->ignore($level)
            ],
            'desc' => [
                'nullable',
                'string',
                Rule::unique(Level::class)->ignore($level)
            ],
            'audio_track' => [
                'required_without:song_id',
                'integer',
                Rule::unique(Level::class)->ignore($level)
            ],
            'song_id' => [
                'required_without:audio_track',
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($level)
            ],
            'password' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($level)
            ],
            'requested_stars' => [
                'required',
                'integer',
                Rule::unique(Level::class)->ignore($level)
            ],
            'unlisted' => [
                'required',
                'boolean',
                Rule::unique(Level::class)->ignore($level)
            ]
        ];
    }
}
