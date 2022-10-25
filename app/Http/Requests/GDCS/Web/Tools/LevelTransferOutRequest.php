<?php

namespace App\Http\Requests\GDCS\Web\Tools;

use App\Http\Requests\Request;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelTransferOutRequest extends Request
{
    public function rules(): array
    {
        return [
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id')
            ],
            'linkID' => [
                'required',
                'integer',
                Rule::exists(AccountLink::class, 'id')
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }
}
