<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelCommentUploadRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'userName' => [
				'required',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'comment' => [
				'required',
				'string'
			],
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id')
			],
			'percent' => [
				'nullable',
				'integer'
			],
			'chk' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}