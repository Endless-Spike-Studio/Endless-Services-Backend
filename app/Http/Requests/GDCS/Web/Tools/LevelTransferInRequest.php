<?php

namespace App\Http\Requests\GDCS\Web\Tools;

use App\Http\Requests\Request;
use App\Models\GDCS\AccountLink;
use Illuminate\Validation\Rule;

class LevelTransferInRequest extends Request
{
    public function rules(): array
    {
        return [
            'linkID' => [
                'required',
                'integer',
                Rule::exists(AccountLink::class, 'id')
            ],
            'levelID' => [
                'required',
                'integer'
            ]
        ];
    }
}
