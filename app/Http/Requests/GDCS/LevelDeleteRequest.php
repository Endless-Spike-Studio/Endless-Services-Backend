<?php

namespace App\Http\Requests\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelDeleteRequest extends Request
{
    public function authorize(): bool
    {
        return $this->auth() && !empty($this->user);
    }

    public function rules(): array
    {
        return [
            'gameVersion' => [
                'required',
                'integer'
            ],
            'binaryVersion' => [
                'required',
                'integer'
            ],
            'gdw' => [
                'required',
                'boolean'
            ],
            'accountID' => [
                'sometimes',
                'exclude_if:accountID,0',
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'gjp' => [
                'required_with:accountID',
                'nullable',
                'string'
            ],
            'uuid' => [
                'required_without:accountID',
                'integer'
            ],
            'udid' => [
                'required_with:uuid',
                'string'
            ],
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id')
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfv2898gc9'
            ]
        ];
    }
}
