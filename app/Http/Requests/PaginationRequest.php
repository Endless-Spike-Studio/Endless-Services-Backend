<?php

namespace App\Http\Requests;

class PaginationRequest extends Request
{
    public function rules(): array
    {
        return [
            'page' => [
                'required',
                'integer'
            ]
        ];
    }
}
