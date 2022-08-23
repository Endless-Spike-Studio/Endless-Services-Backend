<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountFriendRequestDeleteRequest extends Request
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
            'targetAccountID' => [
                'different:accountID',
                'exclude_if:targetAccountID,0',
                'required_without:accounts',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'accounts' => [
                'different:accountID',
                'required_without:targetAccountID',
                'string',
                Rule::exists(Account::class, 'id'),
            ],
            'isSender' => [
                'required',
                'boolean',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
