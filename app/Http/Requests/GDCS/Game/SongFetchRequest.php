<?php

namespace App\Http\Requests\GDCS\Game;

class SongFetchRequest extends Request
{
	public function rules(): array
	{
		return [
			'songID' => [
				'required',
				'integer',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
		];
	}
}
