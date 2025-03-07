<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelCommentMode;
use Illuminate\Validation\Rule;

class GameLevelCommentListRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			...$this->pagination(),
			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id')
			],
			'mode' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelCommentMode::class)
			],
			...$this->secret()
		];
	}
}