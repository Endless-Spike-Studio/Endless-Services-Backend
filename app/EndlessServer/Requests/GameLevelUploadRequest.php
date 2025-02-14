<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashLevelLengths;
use App\GeometryDash\Enums\GeometryDashLevelUnlistedTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameLevelUploadRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->identifies(),
			...$this->auth_gjp2(true),
			'userName' => [
				'required',
				'string'
			],
			'levelID' => [
				'required',
				'integer',
				'exclude_if:levelID,0',
				Rule::exists(Level::class, 'id')
			],
			'levelName' => [
				'required',
				'string'
			],
			'levelDesc' => [
				'nullable',
				'string'
			],
			'levelVersion' => [
				'required',
				'integer'
			],
			'levelLength' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelLengths::class)
			],
			'audioTrack' => [
				'required_if:songID,0',
				'integer'
			],
			'auto' => [
				'required',
				'boolean'
			],
			'password' => [
				'required',
				'integer'
			],
			'original' => [
				'required',
				'integer',
				'exclude_if:original,0',
				Rule::exists(Level::class, 'id')
			],
			'twoPlayer' => [
				'required',
				'boolean'
			],
			'songID' => [
				'required',
				'integer'
			],
			'objects' => [
				'required',
				'integer'
			],
			'coins' => [
				'required',
				'integer'
			],
			'requestedStars' => [
				'required',
				'integer'
			],
			'unlisted' => [
				'required',
				'integer',
				Rule::enum(GeometryDashLevelUnlistedTypes::class)
			],
			'wt' => [
				'required',
				'integer'
			],
			'wt2' => [
				'required',
				'integer'
			],
			'ldm' => [
				'required',
				'boolean'
			],
			'ts' => [
				'required',
				'integer'
			],
			'seed' => [
				'required',
				'string'
			],
			'seed2' => [
				'required',
				'string'
			],
			'levelString' => [
				'required',
				'string'
			],
			'levelInfo' => [
				'nullable',
				'string'
			],
			'extraString' => [
				'nullable',
				'string'
			],
			'songIDs' => [
				'nullable',
				'string'
			],
			'sfxIDs' => [
				'nullable',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		return Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->check();
	}
}