<?php

namespace App\Http\Requests\GDCS\Game;

class TopArtistFetchRequest extends Request
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
			'page' => [
				'required',
				'integer',
				'min:0',
			],
			'secret' => [
				'required',
				'string',
				'in:Wmfd2893gb7',
			],
			'total' => [
				'required',
				'integer',
			],
		];
	}
}
