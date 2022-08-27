<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelCommentCreateRequest extends Request
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
            'userName' => [
                'required',
                'string',
            ],
            'comment' => [
                'required',
                'string',
            ],
            'chk' => [
                'required',
                'string',
            ],
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id'),
            ],
            'percent' => [
                'sometimes',
                'required',
                'integer',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
