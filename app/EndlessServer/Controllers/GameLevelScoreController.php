<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelNormalScore;
use App\EndlessServer\Models\LevelPlatformerScore;
use App\EndlessServer\Objects\GameLeaderboardObject;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GameNormalLevelScoreLoadRequest;
use App\EndlessServer\Requests\GamePlatformerLevelScoreLoadRequest;
use App\GeometryDash\Enums\GeometryDashLevelScoreModes;
use App\GeometryDash\Enums\GeometryDashLevelScoreTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelScoreController
{
	public function __construct(
		protected accountFriendRepository $accountFriendRepository
	)
	{

	}

	public function loadNormal(GameNormalLevelScoreLoadRequest $request): string
	{
		$data = $request->validated();

		/** @var ?Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		if ($account !== null) {
			LevelNormalScore::query()
				->updateOrCreate([
					'account_id' => $account->id,
					'level_id' => $level->id
				], [
					'percent' => $data['percent'],
					'attempts' => ($data['s1'] - 8354) ?? $data['s8'],
					'clicks' => $data['s2'] - 3991,
					'attempt_seconds' => $data['s3'] - 4085,
					'coins' => $data['s9'] - 5819,
					'special_id' => $data['s10']
				]);
		}

		$query = LevelNormalScore::query();

		switch ($data['type']) {
			case GeometryDashLevelScoreTypes::FRIENDS->value:
				if ($account === null) {
					return GeometryDashResponses::LEVEL_NORMAL_SCORE_LOAD_FAILED_NO_LOGIN->value;
				}

				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id)->add($account->id);

				$query->whereIn('account_id', $friendAccountIDs);
				$query->orderByDesc('percent');
				break;
			case GeometryDashLevelScoreTypes::TOP->value:
				$query->orderByDesc('percent');
				break;
			case GeometryDashLevelScoreTypes::WEEK->value:
				$query->where('created_at', '>=', now()->subWeek());
				break;
		}

		return $query->get()
			->map(function (LevelNormalScore $score) {
				return new GameLeaderboardObject($score)->merge();
			})->join(GeometryDashLeaderboardObjectDefinitions::SEPARATOR);
	}

	public function loadPlatformer(GamePlatformerLevelScoreLoadRequest $request): string
	{
		$data = $request->validated();

		/** @var ?Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		if ($account !== null) {
			LevelPlatformerScore::query()
				->updateOrCreate([
					'account_id' => $account->id,
					'level_id' => $level->id
				], [
					'time' => $data['time'],
					'points' => $data['points'],
					'attempts' => ($data['s1'] - 8354) ?? $data['s8'],
					'clicks' => $data['s2'] - 3991,
					'attempt_seconds' => $data['s3'] - 4085,
					'coins' => $data['s9'] - 5819,
					'special_id' => $data['s10']
				]);
		}

		$query = LevelPlatformerScore::query();

		switch ($data['type']) {
			case GeometryDashLevelScoreTypes::FRIENDS->value:
				if ($account === null) {
					return GeometryDashResponses::LEVEL_NORMAL_SCORE_LOAD_FAILED_NO_LOGIN->value;
				}

				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id)->add($account->id);

				$query->whereIn('account_id', $friendAccountIDs);
				break;
			case GeometryDashLevelScoreTypes::WEEK->value:
				$query->where('created_at', '>=', now()->subWeek());
				break;
		}

		switch ($data['mode']) {
			case GeometryDashLevelScoreModes::TIME->value:
				$query->orderByDesc('time');
				break;
			case GeometryDashLevelScoreModes::POINTS->value:
				$query->orderByDesc('points');
				break;
		}

		return $query->get()
			->map(function (LevelPlatformerScore $score) {
				return new GameLeaderboardObject($score)->merge();
			})->join(GeometryDashLeaderboardObjectDefinitions::SEPARATOR);
	}
}