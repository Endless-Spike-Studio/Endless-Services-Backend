<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\AccountComment;
use App\EndlessServer\Traits\GameRequestRules;
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
			'commentID' => [
				'required',
				'integer',
				Rule::exists(AccountComment::class, 'id')
			],
			...$this->secret()
		];
	}
}
