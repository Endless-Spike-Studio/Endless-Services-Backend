<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Enums\GeometryDashBinaryVersions;
use App\GeometryDash\Enums\GeometryDashCommentType;
use App\GeometryDash\Enums\GeometryDashGameVersions;
use App\GeometryDash\Enums\GeometryDashSecrets;
use Illuminate\Validation\Rule;

class GameAccountCommentUploadRequest extends GameRequest
{
    public function rules(): array
    {
        return [
            'gameVersion' => [
                'nullable',
                'integer',
                Rule::in([
                    GeometryDashGameVersions::LATEST->value
                ])
            ],
            'binaryVersion' => [
                'nullable',
                'integer',
                Rule::in([
                    GeometryDashBinaryVersions::LATEST->value
                ])
            ],
            'gdw' => [
                'nullable',
                'integer'
            ],
            'accountID' => [
                'required',
                'integer',
                Rule::exists(Account::class, 'id')
            ],
            'gjp2' => [
                'required',
                'string'
            ],
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
            'secret' => [
                'required',
                'string',
                Rule::in([
                    GeometryDashSecrets::COMMON->value
                ])
            ]
        ];
    }
}