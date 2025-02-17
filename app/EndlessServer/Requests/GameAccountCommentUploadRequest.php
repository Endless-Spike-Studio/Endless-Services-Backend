<?php

namespace App\EndlessServer\Requests;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Traits\GameRequestRules;
use App\GeometryDash\Enums\GeometryDashCommentTypes;
use App\GeometryDash\Services\GeometryDashCheckService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GameAccountCommentUploadRequest extends GameRequest
{
	use GameRequestRules;

	public function rules(): array
	{
		return [
			...$this->versions(),
			...$this->gdw(),
			...$this->auth_gjp2(),
			...$this->identifies(),
			'userName' => [
				'nullable',
				'string',
				Rule::exists(Account::class, 'name')
			],
			'comment' => [
				'required',
				'string'
			],
			'cType' => [
				'required',
				'integer',
				Rule::enum(GeometryDashCommentTypes::class),
				Rule::in([
					GeometryDashCommentTypes::ACCOUNT->value
				])
			],
			'chk' => [
				'required',
				'string'
			],
			...$this->secret()
		];
	}

	public function authorize(): bool
	{
		$this->getValidatorInstance();

		$data = $this->validated();

		return Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->check() && $data['chk'] === app(GeometryDashCheckService::class)->generateAccountComment($data['userName'], $data['comment']);
	}
}