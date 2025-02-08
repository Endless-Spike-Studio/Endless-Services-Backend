<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameAccountMessageDeleteRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'messageID' => [
				'nullable',
				'integer',
				Rule::exists(AccountMessage::class, 'id')
			],
			'messages' => [
				'nullable',
				'string'
			],
			'isSender' => [
				'nullable',
				'boolean'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}