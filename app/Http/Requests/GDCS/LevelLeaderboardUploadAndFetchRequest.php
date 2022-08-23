<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\GDCS\Game\Request;
use App\Models\GDCS\Account;
use Illuminate\Validation\Rule;

class LevelLeaderboardUploadAndFetchRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && ! empty($this->account);
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
            'levelID' => [
                'required',
                'integer',
            ],
            'percent' => [
                'required',
                'integer',
                'between:0,100',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
            'type' => [
                'required',
                'integer',
                'between:0,2',
            ],
            's1' => [
                'required',
                'integer',
                'min:8354',
            ],
            's2' => [
                'required',
                'integer',
                'min:3991',
            ],
            's3' => [
                'required',
                'integer',
                'min:4085',
            ],
            's4' => [
                'required',
                'integer',
            ],
            's5' => [
                'required',
                'integer',
            ],
            's6' => [
                'nullable',
                'string',
            ],
            's7' => [
                'required',
                'string',
            ],
            's8' => [
                'required',
                'integer',
            ],
            's9' => [
                'required',
                'integer',
                'min:5819',
            ],
            's10' => [
                'required',
                'integer',
            ],
            'chk' => [
                'required',
                'string',
            ],
        ];
    }
}
