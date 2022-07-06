<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\User;
use Illuminate\Validation\Rule;

class AccountCommentHistoryFetchRequest extends Request
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
            'userID' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id'),
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
