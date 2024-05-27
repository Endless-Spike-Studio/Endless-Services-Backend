<?php

namespace App\Http\Requests\GDCS\Game;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use App\Models\GDCS\TempLevelUploadAccess;
use Illuminate\Validation\Rule;

class LevelUploadRequest extends Request
{
	public function authorize(): bool
	{
		$ip = $this->ip();
		$query = TempLevelUploadAccess::query()
			->where('ip', $ip);

		if ($query->exists()) {
			$record = $query->firstOrFail();
			$this->user = $record->account->user;
			$record->delete();

			return true;
		}

		return $this->auth() && !empty($this->user);
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
				'sometimes',
				'exclude_if:accountID,0',
				Rule::requiredIf(function () {
					return !TempLevelUploadAccess::query()
						->where('ip', $this->ip())
						->exists();
				}),
				'integer',
				Rule::exists(Account::class, 'id'),
			],
			'gjp' => [
				'required_with:accountID',
				'nullable',
				'string',
			],
			'uuid' => [
				'required_without:accountID',
				'integer',
			],
			'udid' => [
				'required_with:uuid',
				'string',
			],
			'levelID' => [
				'exclude_if:levelID,0',
				'required',
				'integer',
				Rule::exists(Level::class, 'id'),
			],
			'levelName' => [
				'required',
				'string',
			],
			'levelDesc' => [
				'present',
				'nullable',
				'string',
			],
			'levelVersion' => [
				'required',
				'integer',
				'min:0',
			],
			'levelLength' => [
				'required',
				'integer',
				'between:0,4',
			],
			'audioTrack' => [
				'required',
				'integer',
				'between:-1,37',
			],
			'auto' => [
				'required',
				'boolean',
			],
			'password' => [
				'required',
				'integer',
			],
			'original' => [
				'exclude_if:original,0',
				'required',
				'integer'
			],
			'twoPlayer' => [
				'required',
				'boolean',
			],
			'songID' => [
				'required',
				'integer',
			],
			'objects' => [
				'required',
				'integer',
			],
			'coins' => [
				'required',
				'integer',
			],
			'requestedStars' => [
				'required',
				'integer',
				'between:0,10',
			],
			'unlisted' => [
				'required',
				'boolean',
			],
			'wt' => [
				'required',
				'integer',
			],
			'wt2' => [
				'required',
				'integer',
			],
			'ldm' => [
				'required',
				'boolean',
			],
			'extraString' => [
				'required',
				'string',
			],
			'seed' => [
				'required',
				'string',
			],
			'seed2' => [
				'required',
				'string',
			],
			'levelString' => [
				'required',
				'string',
			],
			'levelInfo' => [
				'required',
				'string',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
