<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountMessageFetchRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && !empty($this->account);
    }

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
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required',
                'string'
            ],
            'page' => [
                'required',
                'integer',
                'min:0'
            ],
            'total' => [
                'required',
                'integer',
                'min:0'
            ],
            'getSent' => [
                'sometimes',
                'boolean'
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7'
            ]
        ];
    }
}
