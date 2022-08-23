<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\GDCS\Game\Request;
use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelSuggestStarRequest extends Request
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
                Rule::exists(Level::class, 'id'),
            ],
            'stars' => [
                'required',
                'integer',
                'between:1,10',
            ],
            'feature' => [
                'required',
                'boolean',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfp3879gc3',
            ],
        ];
    }
}
