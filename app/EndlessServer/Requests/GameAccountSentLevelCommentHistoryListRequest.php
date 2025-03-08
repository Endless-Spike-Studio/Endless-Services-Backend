<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Player;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelCommentModes;
use Illuminate\Validation\Rule;

class GameAccountSentLevelCommentHistoryListRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			...$this->pagination(),
			'mode' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelCommentModes::class)
			],
			'userID' => [
				'required',
				'integer',
				Rule::exists(Player::class, 'id')
			],
			...$this->secret()
		];
	}
}