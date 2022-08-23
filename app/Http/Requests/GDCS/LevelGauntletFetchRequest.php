<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\GDCS\Game\Request;

class LevelGauntletFetchRequest extends Request
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
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
