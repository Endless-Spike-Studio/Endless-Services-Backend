<?php

namespace App\Http\Requests\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Models\GDCS\Account;
use App\Models\GDCS\User;
use App\Services\Game\AlgorithmService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Request extends FormRequest
{
	public Account $account;

	public User $user;

	public function rules(): array
	{
		return [];
	}

	public function auth(): bool
	{
		return $this->authAccount()
			|| $this->authAccountUsingName()
			|| $this->authUser();
	}

	protected function authAccount(): bool
	{
		if (!$this->filled(['accountID', 'gjp'])) {
			return false;
		}

		$accountID = $this->get('accountID');
		$gjp = $this->get('gjp');

		try {
			$this->account = Account::query()
				->findOrFail($accountID);

			$password = AlgorithmService::decode($gjp, Keys::ACCOUNT_PASSWORD->value);
			if (!Hash::check($password, $this->account->password)) {
				return false;
			}

			$this->user = $this->account->user ?? $this->newPlayer();

			return !empty($this->user);
		} catch (ModelNotFoundException) {
			return false;
		}
	}

	public function newPlayer(): ?User
	{
		$randomUUID = Str::uuid()
			->toString();

		$udid = $this->get('udid', $randomUUID);
		$uuid = $this->account->id ?? $udid;
		$name = $this->get('userName', $this->account->name ?? 'Player');

		return User::query()
			->firstOrCreate(['uuid' => $uuid], ['name' => $name, 'udid' => $udid]);
	}

	protected function authAccountUsingName(): bool
	{
		if (!$this->filled(['userName', 'password'])) {
			return false;
		}

		$name = $this->get('userName');
		$password = $this->get('password');

		try {
			$this->account = Account::query()
				->whereName($name)
				->firstOrFail();

			if (!Hash::check($password, $this->account->password)) {
				return false;
			}

			$this->user = $this->account->user ?? $this->newPlayer();

			return !empty($this->user);
		} catch (ModelNotFoundException) {
			return false;
		}
	}

	protected function authUser(): bool
	{
		if (!$this->filled(['uuid', 'udid'])) {
			return false;
		}

		$uuid = $this->get('uuid');
		$udid = $this->get('udid');

		try {
			$user = User::query()
				->whereKey($uuid)
				->orWhere('uuid', $udid)
				->where('udid', $udid)
				->firstOrFail();

			$this->user = $user;
			if (!empty($this->user->account)) {
				$this->account = $this->user->account;
			}

			return !empty($this->user);
		} catch (ModelNotFoundException) {
			$this->user = $this->newPlayer();

			return !empty($this->user);
		}
	}

	/**
	 * @throws GeometryDashChineseServerException
	 */
	protected function failedAuthorization(): void
	{
		throw new GeometryDashChineseServerException(__('gdcn.game.error.request_authorization_failed'), gameResponse: Response::GAME_REQUEST_AUTHORIZATION_FAILED->value);
	}

	/**
	 * @throws GeometryDashChineseServerException
	 */
	protected function failedValidation(Validator $validator): void
	{
		throw new GeometryDashChineseServerException(__('gdcn.game.error.request_validate_failed'), logContext: [
			'errors' => $validator->errors()
				->toArray()
		], gameResponse: Response::GAME_REQUEST_VALIDATE_FAILED->value);
	}
}
