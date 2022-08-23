<?php

namespace App\Http\Requests\GDCS;

use App\Http\Requests\GDCS\Game\Request;
use App\Models\GDCS\User;
use Illuminate\Validation\Rule;

class ItemRestoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'udid' => [
                'required',
                'integer',
                Rule::exists(User::class, 'udid'),
            ],
            'secret' => [
                'required',
                'string',
                'in:Wmfd2893gb7',
            ],
        ];
    }
}
