<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\GDCS\Game\Request;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelCommentFetchRequest extends Request
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
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id'),
            ],
            'page' => [
                'required',
                'integer',
                'min:0',
            ],
            'total' => [
                'required',
                'integer',
                'min:0',
            ],
            'mode' => [
                'required',
                'between:0,1',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
