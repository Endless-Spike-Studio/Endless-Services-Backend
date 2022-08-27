<?php

namespace App\Http\Requests\GDCS\Game;

class LevelPackFetchRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'integer',
            ],
            'binaryVersion' => [
                'required',
                'integer',
            ],
            'gdw' => [
                'required',
                'boolean',
            ],
            'page' => [
                'required',
                'integer',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
