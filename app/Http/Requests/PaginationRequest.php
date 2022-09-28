<?php

namespace App\Http\Requests;

class PaginationRequest extends Request{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
