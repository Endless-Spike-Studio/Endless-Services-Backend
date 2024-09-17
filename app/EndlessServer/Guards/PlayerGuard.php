<?php

namespace App\EndlessServer\Guards;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Services\GameAccountService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PlayerGuard implements Guard
{
	protected ?Player $player = null;

	public function id(): ?int
	{
		if ($this->guest()) {
			return null;
		}

		return $this->player->id;
	}

	public function guest(): bool
	{
		return null !== $this->user();
	}

	public function user(): ?Player
	{
		if (!empty($this->player)) {
			return $this->player;
		}

		if ($this->tryAccount()) {
			return $this->user();
		}

		if (Request::filled(['uuid', 'udid'])) {
			if ($this->tryUuidAndUdid()) {
				return $this->user();
			}

			if ($this->tryCreate()) {
				return $this->user();
			}
		}

		return null;
	}

	protected function tryAccount(): bool
	{
		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		if (!empty($account)) {
			$udid = Request::string('udid');
			$this->player = app(GameAccountService::class)->queryAccountPlayer($account, $udid);
			return true;
		}

		return false;
	}

	protected function tryUuidAndUdid(): bool
	{
		$uuid = Request::string('uuid');
		$udid = Request::string('udid');

		if (is_numeric($uuid)) {
			$player = Player::query()
				->where('id', $uuid)
				->where('udid', $udid)
				->first();
		} else {
			$player = Player::query()
				->where('uuid', $uuid)
				->where('udid', $udid)
				->first();
		}

		if (empty($player)) {
			return false;
		}

		$this->player = $player;
		return true;
	}

	protected function tryCreate()
	{
		$uuid = Request::string('uuid');
		$udid = Request::string('udid');
		$name = Request::string('userName', 'Player');

		$player = Player::query()
			->create([
				'uuid' => $uuid,
				'udid' => $udid,
				'name' => $name
			]);

		if (!$player->wasRecentlyCreated) {
			return false;
		}

		$this->player = $player;
		return true;
	}

	public function check(): bool
	{
		return !$this->guest();
	}

	public function validate(array $credentials = []): false
	{
		return false;
	}

	public function hasUser(): bool
	{
		return !$this->guest();
	}

	public function setUser(Authenticatable $user): PlayerGuard
	{
		return $this;
	}
}