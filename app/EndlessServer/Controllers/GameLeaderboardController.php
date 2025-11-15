<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Objects\GameLeaderboardObject;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GameLeaderboardListRequest;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashLeaderboardType;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

readonly class GameLeaderboardController
{
	public function __construct(
		protected GeometryDashObjectService  $objectService,
		protected GameAccountService         $accountService,
		protected GamePlayerDataService      $playerDataService,
		protected GamePlayerStatisticService $playerStatisticService,
		protected AccountFriendRepository    $accountFriendRepository
	)
	{

	}

	/**
	 * @throws EndlessServerGameException
	 */
	public function list(GameLeaderboardListRequest $request): string
	{
		$data = $request->validated();

		if (!isset($data['count'])) {
			$data['count'] = 0;
		}

		$query = Player::query();

		switch ($data['type']) {
			case GeometryDashLeaderboardType::TOP->value:
				$query->with('data', function (Builder $query) {
					$query->orderByDesc('stars');
				});
				break;
			case GeometryDashLeaderboardType::FRIENDS->value:
				/** @var Account $account */
				$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id);

				$friendAccountPlayerIDs = Account::query()
					->whereIn('id', $friendAccountIDs)
					->get()
					->map(function (Account $account) {
						return $account->player->id;
					});

				$query->whereIn('id', $friendAccountPlayerIDs);
				break;
			case GeometryDashLeaderboardType::RELATIVE->value:
				$half = round($data['count'] / 2);

				/** @var Account $account */
				$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

				$query->with('data', function (Builder $query) use ($half, $account) {
					$query->where('stars', '<=', $account->player->data->stars);

					$query->take($half);
				});

				$rightQuery = Player::query()
					->with('data', function (Builder $query) use ($half, $account) {
						$query->where('stars', '>', $account->player->data->stars);

						$query->take($half);
					});

				$query->union($rightQuery);
				break;
			case GeometryDashLeaderboardType::CREATORS->value:
				$query->with('statistic', function (Builder $query) {
					$query->orderByDesc('creator_points');
				});
				break;
			default:
				throw new EndlessServerGameException(
					__('排行榜获取失败: 无效的类型')
				);
		}

		$query->take($data['count']);

		return $query->get()
			->map(function (Player $player, int $index) use ($request) {
				return new GameLeaderboardObject($player, $index + 1)->except([
					GeometryDashLeaderboardObjectDefinitions::AGE->value
				])->merge();
			})
			->join(GeometryDashLeaderboardObjectDefinitions::SEPARATOR);
	}
}