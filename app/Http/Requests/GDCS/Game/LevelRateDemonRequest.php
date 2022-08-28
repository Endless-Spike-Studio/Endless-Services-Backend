<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class LevelRateDemonRequest extends Request
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
            'mode' => [
                'sometimes',
                'required',
                'in:1',
            ],
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id'),
            ],
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfp3879gc3',
            ],
        ];
    }
}
