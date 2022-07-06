<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelRateStarRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && ! empty($this->user);
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
                Rule::exists(Level::class, 'id'),
            ],
            'stars' => [
                'required',
                'integer',
                'between:1,10',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
            'rs' => [
                'required',
                'string',
            ],
            'chk' => [
                'required',
                'string',
            ],
        ];
    }
}
