<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Exceptions\EndlessServerGameException;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GameLeaderboardListRequest;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashLeaderboardType;
use App\GeometryDash\Enums\Objects\GeometryDashLeaderboardObjectDefinitions;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
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
						return $this->accountService->queryAccountPlayer($account)->id;
					});

				$query->whereIn('id', $friendAccountPlayerIDs);
				break;
			case GeometryDashLeaderboardType::RELATIVE->value:
				$half = round($data['count'] / 2);

				/** @var Account $account */
				$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

				$query->with('data', function (Builder $query) use ($half, $account) {
					$player = $this->accountService->queryAccountPlayer($account);

					$this->playerDataService->initialize($player->id);
					$this->playerStatisticService->initialize($player->id);

					$query->where('stars', '<=', $player->data->stars);

					$query->take($half);
				});

				$rightQuery = Player::query()
					->with('data', function (Builder $query) use ($half, $account) {
						$player = $this->accountService->queryAccountPlayer($account);

						$this->playerDataService->initialize($player->id);
						$this->playerStatisticService->initialize($player->id);

						$query->where('stars', '>', $player->data->stars);

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
			->map(function (Player $player, int $index) {
				$this->playerDataService->initialize($player->id);
				$this->playerStatisticService->initialize($player->id);

				return $this->objectService->merge([
					GeometryDashLeaderboardObjectDefinitions::PLAYER_NAME->value => $player->name,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_ID->value => $player->id,
					GeometryDashPlayerInfoObjectDefinitions::STARS->value => $player->data->stars,
					GeometryDashPlayerInfoObjectDefinitions::DEMONS->value => $player->data->demons,
					GeometryDashLeaderboardObjectDefinitions::RANKING->value => $index + 1,
					GeometryDashPlayerInfoObjectDefinitions::CREATOR_POINTS->value => $player->statistic->creator_points,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_ID->value => $player->data->icon_id,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_1->value => $player->data->color1,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_COLOR_2->value => $player->data->color2,
					GeometryDashPlayerInfoObjectDefinitions::COINS->value => $player->data->coins,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_ICON_TYPE->value => $player->data->icon_type,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_SPECIAL->value => $player->data->special,
					GeometryDashLeaderboardObjectDefinitions::PLAYER_UUID->value => $player->uuid,
					GeometryDashPlayerInfoObjectDefinitions::USER_COINS->value => $player->data->user_coins,
					GeometryDashPlayerInfoObjectDefinitions::DIAMONDS->value => $player->data->diamonds,
				], GeometryDashLeaderboardObjectDefinitions::GLUE);
			})->join('|');
	}
}