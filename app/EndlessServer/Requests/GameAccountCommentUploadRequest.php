<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashCommentType;
use Illuminate\Validation\Rule;

class GameAccountCommentUploadRequest extends GameRequest
{
    use GameRequestRules;

    public function rules(): array
    {
        return [
            ...$this->versions(),
            ...$this->gdw(),
            ...$this->auth_gjp2(),
            'comment' => [
                'required',
                'string'
            ],
            'cType' => [
                'required',
                'integer',
                Rule::enum(GeometryDashCommentType::class)
            ],
            'chk' => [
                'required',
                'string'
            ],
            ...$this->secret()
        ];
    }
}