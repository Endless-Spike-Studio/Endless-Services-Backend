<?php

namespace App\Http\Requests\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Models\GDCS\Account;
use App\Services\Game\AlgorithmService;
use Illuminate\Validation\Rule;

class AccountCommentCreateRequest extends Request
{
	public function authorize(): bool
	{
		return $this->auth() && !empty($this->account) && $this->validateChk();
	}

	protected function validateChk(): bool
	{
		return hash_equals(
			AlgorithmService::encode($this->get('userName') . $this->get('comment') . $this->get('levelID', 0) . $this->get('percent', 0) . $this->get('cType') . Salts::COMMENT->value, Keys::COMMENT_CHK->value),
			$this->get('chk')
		);
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
			'userName' => [
				'required',
				'string',
			],
			'comment' => [
				'required',
				'string',
			],
			'cType' => [
				'required',
				'integer',
				'in:1',
			],
			'chk' => [
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
