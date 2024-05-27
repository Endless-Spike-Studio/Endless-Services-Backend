<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use Illuminate\Validation\Rule;

class LevelCommentDeleteRequest extends Request
{
	public function authorize(): bool
	{
		return $this->auth() && !empty($this->account);
	}

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
			'accountID' => [
				'required',
				'integer',
				Rule::exists(Account::class, 'id'),
			],
			'gjp' => [
				'required',
				'string',
			],
			'commentID' => [
				'required',
				'integer',
				Rule::exists(LevelComment::class, 'id'),
			],

			'levelID' => [
				'required',
				'integer',
				Rule::exists(Level::class, 'id'),
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
