<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountInfoFetchRequest extends Request
{
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'integer'
            ],
            'binaryVersion' => [
                'required',
                'integer'
            ],
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'sometimes',
                'exclude_if:accountID,0',
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable',
                'string'
            ],
            'targetAccountID' => [
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7'
            ]
        ];
    }
}
