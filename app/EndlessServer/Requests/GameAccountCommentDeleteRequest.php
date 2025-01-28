<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountComment;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashCommentType;
use Illuminate\Validation\Rule;

class GameAccountCommentDeleteRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->auth_gjp2(),
			'uuid' => [
				'nullable',
				'string'
			],
			'udid' => [
				'nullable',
				'string'
			],
			'commentID' => [
				'required',
				'integer',
				Rule::exists(AccountComment::class, 'id')
			],
			'cType' => [
				'required',
				'integer',
				Rule::enum(GeometryDashCommentType::class),
				Rule::in([
					GeometryDashCommentType::ACCOUNT->value
				])
			],
			'targetAccountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id')
			],
			...$this->secret()
		];
	}
}
