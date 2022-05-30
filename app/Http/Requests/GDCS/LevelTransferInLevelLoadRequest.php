<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use App\Models\GDCS\AccountLink;
use Illuminate\Validation\Rule;

class LevelTransferInLevelLoadRequest extends Request
{
    public function rules(): array
    {
        return [
            'link' => [
                'sometimes',
                'required',
                Rule::in(AccountLink::class, 'id')
            ]
        ];
    }
}
