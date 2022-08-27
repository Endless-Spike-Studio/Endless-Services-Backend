<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class UserFetchRequest extends Request
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
            'accountID' => [
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'gjp' => [
                'required',
                'string',
            ],
            'type' => [
                'required',
                'integer',
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
