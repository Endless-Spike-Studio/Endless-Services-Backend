<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class LevelTransferToolInRequest extends Request
{
    public function rules(): array
    {
        return [
            'levelID' => [
                'required',
                'integer'
            ]
        ];
    }
}
