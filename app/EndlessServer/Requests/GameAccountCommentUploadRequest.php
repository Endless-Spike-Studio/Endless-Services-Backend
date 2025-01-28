<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Account;
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
			'uuid' => [
				'nullable',
				'string'
			],
			'udid' => [
				'nullable',
				'string'
			],
			'userName' => [
				'nullable',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'comment' => [
				'required',
				'string'
			],
			'cType' => [
				'required',
				'integer',
				Rule::enum(GeometryDashCommentType::class),
				Rule::in([
					GeometryDashCommentType::ACCOUNT->value
				])
			],
			'chk' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}
}