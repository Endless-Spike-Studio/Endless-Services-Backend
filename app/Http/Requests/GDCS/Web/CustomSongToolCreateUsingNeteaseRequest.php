<?php

namespace App\Http\Requests\GDCS\Web;

use App\Http\Requests\Request;

class CustomSongToolCreateUsingNeteaseRequest extends Request
{
	public function rules(): array
	{
		return [
			'music_id' => [
				'required',
				'integer'
			]
		];
	}
}
