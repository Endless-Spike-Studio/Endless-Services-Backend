<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class LevelDownloadRequest extends Request
{
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
                'sometimes',
                'exclude_if:accountID,0',
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable',
                'string',
            ],
            'uuid' => [
                'sometimes',
                'required_without:accountID',
                'integer',
            ],
            'udid' => [
                'required_with:uuid',
                'string',
            ],
            'levelID' => [
                'required',
                'integer',
            ],
            'inc' => [
                'sometimes',
                'required',
                'boolean',
            ],
            'extras' => [
                'sometimes',
                'required',
                'boolean',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
            'rs' => [
                'sometimes',
                'required',
                'string',
            ],
            'chk' => [
                'sometimes',
                'required',
                'string',
            ],
        ];
    }
}
