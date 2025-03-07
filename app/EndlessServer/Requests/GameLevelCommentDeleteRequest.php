<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelComment;
use App\EndlessServer\Traits\GameRequestRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelCommentDeleteRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(),
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id')
			],
			'commentID' => [
				'required',
				'integer',
				Rule::exists(LevelComment::class, 'id')
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check();
	}
}