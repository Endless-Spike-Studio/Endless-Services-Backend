<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class CustomSongToolCreateUsingLinkRequest extends Request
{
	public function rules(): array
	{
		return [
			'name' => [
				'required',
				'string'
			],
			'artist_name' => [
				'required',
				'string'
			],
			'link' => [
				'required',
				'string',
				'active_url'
			]
		];
	}
}
