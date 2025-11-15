<?php

namespace App\EndlessProxy\Requests;

use App\Api\Requests\ApiRequest;

class GameFetchSongObjectRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'songID' => [
				'required',
				'integer'
			]
		];
	}
}