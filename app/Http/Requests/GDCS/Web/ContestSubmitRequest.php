<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;
use App\Models\GDCS\Level;
use Illuminate\Validation\Rule;

class ContestSubmitRequest extends Request
{
    public function rules(): array
    {
        return [
            'levelID' => [
                'required',
                'integer',
                Rule::exists(Level::class, 'id')
            ]
        ];
    }
}
