<?php

namespace App\Http\Requests\GDCS;

use App\Enums\Response;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class AccountRegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'userName' => [
                'required',
                'string',
                Rule::unique(Account::class, 'name'),
            ],
            'password' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique(Account::class),
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfv3899gc9',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'userName.unique' => Response::ACCOUNT_REGISTER_USER_NAME_ALREADY_IN_USE->value,
            'email.unique' => Response::ACCOUNT_REGISTER_EMAIL_ALREADY_IN_USE->value,
            'email.email' => Response::ACCOUNT_REGISTER_EMAIL_INVALID->value,
        ];
    }
}
