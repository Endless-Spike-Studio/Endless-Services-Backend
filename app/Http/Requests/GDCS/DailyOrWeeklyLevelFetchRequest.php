<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class DailyOrWeeklyLevelFetchRequest extends Request
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
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                'string'
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7'
            ],
            'weekly' => [
                'required',
                'boolean'
            ]
        ];
    }
}
