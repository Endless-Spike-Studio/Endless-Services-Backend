<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\LevelGauntlet;
use Illuminate\Validation\Rule;

class LevelSearchRequest extends Request
{
	public function rules(): array
	{
		return [
			'gameVersion' => [
				'required',
				'integer',
			],
			'binaryVersion' => [
				'required',
				'integer',
			],
			'gdw' => [
				'required',
				'boolean',
			],
			'type' => [
				'required_without:gauntlet',
				'integer',
				'between:0,18',
			],
			'accountID' => [
				'sometimes',
				'exclude_if:accountID,0',
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
			],
			'gjp' => [
				'required_with:accountID',
				'nullable',
				'string',
			],
			'gauntlet' => [
				'sometimes',
				'required',
				'integer',
				Rule::exists(LevelGauntlet::class, 'id'),
			],
			'str' => [
				Rule::requiredIf(function () {
					return !$this->has('gauntlet') && $this->filled('str');
				}),
				'nullable',
				'string',
			],
			'diff' => [
				'exclude_if:diff,-',
				'required_without:gauntlet',
				'integer',
				'between:-3,5',
			],
			'len' => [
				'exclude_if:len,-',
				'required_without:gauntlet',
				'integer',
				'between:0,4',
			],
			'page' => [
				'required_without:gauntlet',
				'integer',
			],
			'total' => [
				'required_without:gauntlet',
				'integer',
			],
			'uncompleted' => [
				'required_without:gauntlet',
				'boolean',
			],
			'onlyCompleted' => [
				'required_without:gauntlet',
				'boolean',
			],
			'completedLevels' => [
				'sometimes',
				'required',
				'string',
			],
			'featured' => [
				'required_without:gauntlet',
				'boolean',
			],
			'original' => [
				'required_without:gauntlet',
				'boolean',
			],
			'twoPlayer' => [
				'required_without:gauntlet',
				'boolean',
			],
			'coins' => [
				'required_without:gauntlet',
				'boolean',
			],
			'epic' => [
				'sometimes',
				'required',
				'boolean',
			],
			'noStar' => [
				'sometimes',
				'required',
				'boolean',
			],
			'star' => [
				'sometimes',
				'required',
				'boolean',
			],
			'song' => [
				'sometimes',
				'required',
				'integer',
			],
			'customSong' => [
				'sometimes',
				'required',
				'boolean',
			],
			'followed' => [
				'sometimes',
				'nullable',
				'string',
			],
			'demonFilter' => [
				'sometimes',
				'required_if:diff,-2',
				'integer',
				'between:1,5',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
