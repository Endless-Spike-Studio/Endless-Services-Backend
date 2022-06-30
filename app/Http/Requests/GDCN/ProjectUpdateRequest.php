<?php

namespace App\Http\Requests\GDCN;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends Request
{
    public function rules(): array
    {
        return [
            'secret' => [
                'required',
                'string',
                Rule::in([
                    config('project.secret')
                ])
            ]
        ];
    }
}
