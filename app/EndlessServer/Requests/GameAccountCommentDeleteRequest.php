<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountComment;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashCommentTypes;
use Illuminate\Support\Facades\Auth;
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
			...$this->identifies(),
			'commentID' => [
				'required',
				'integer',
				Rule::exists(AccountComment::class, 'id')
			],
			'cType' => [
				'required',
				'integer',
				Rule::enum(GeometryDashCommentTypes::class),
				Rule::in([
					GeometryDashCommentTypes::ACCOUNT->value
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

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}