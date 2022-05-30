<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\Request;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountSettingUpdateApiRequest extends Request
{
    public function rules(): array
    {
        /** @var Account|null $account */
        $account = $this->user('gdcs');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique(Account::class)->ignore($account->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(Account::class)->ignore($account->id)
            ],
            'password' => [
                'exclude_without:password',
                'string',
                'confirmed'
            ]
        ];
    }
}
