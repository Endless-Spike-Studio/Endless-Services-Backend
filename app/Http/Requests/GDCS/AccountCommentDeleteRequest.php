<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\AccountComment;
use Illuminate\Validation\Rule;

class AccountCommentDeleteRequest extends Request
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
            'commentID' => [
                'required',
                'integer',
                Rule::exists(AccountComment::class, 'id'),
            ],
            'cType' => [
                'required',
                'integer',
                'in:1',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
