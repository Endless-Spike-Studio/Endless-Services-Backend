<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountSettingUpdateRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && !empty($this->account);
    }

    public function rules(): array
    {
        return [
            'accountID' => [
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'gjp' => [
                'required',
                'string',
            ],
            'mS' => [
                'required',
                'integer',
                'between:0,2',
            ],
            'frS' => [
                'required',
                'integer',
                'in:0,1',
            ],
            'cS' => [
                'required',
                'integer',
                'between:0,2',
            ],
            'yt' => [
                'present',
                'nullable',
            ],
            'twitter' => [
                'present',
                'nullable',
            ],
            'twitch' => [
                'present',
                'nullable',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfv3899gc9',
            ],
        ];
    }
}
