<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountBlocklist;
use App\EndlessServer\Models\AccountMessage;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Models\PlayerData;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GamePlayerInfoFetchRequest;
use App\EndlessServer\Requests\GamePlayerListRequest;
use App\EndlessServer\Requests\GamePlayerSearchRequest;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GameAccountSettingService;
use App\EndlessServer\Services\GamePaginationService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashPlayerListTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashPlayerInfoObjectDefinitions;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Support\Facades\Auth;

readonly class GamePlayerController
{
	public function __construct(
		protected GeometryDashObjectService  $objectService,
		protected GameAccountService         $accountService,
		protected GameAccountSettingService  $accountSettingService,
		protected GamePlayerDataService      $dataService,
		protected GamePlayerStatisticService $statisticService,
		protected GamePaginationService      $paginationService,
		protected AccountFriendRepository    $accountFriendRepository
	)
	{

	}

	public function list(GamePlayerListRequest $request): string
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$query = Player::query();

		switch ($data['type']) {
			case GeometryDashPlayerListTypes::FRIENDS->value:
				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id);

				$query->whereIn('uuid', $friendAccountIDs); // TODO: optimize
				break;
			case GeometryDashPlayerListTypes::BLOCKLIST->value:
				$blockAccountIDs = AccountBlocklist::query()
					->where('account_id', $account->id)
					->pluck('target_account_id');

				$query->whereIn('uuid', $blockAccountIDs); // TODO: optimize
				break;
			default:
				return GeometryDashResponses::PLAYER_LIST_FAILED_INVALID_TYPE->value;
		}

		return $query->get()
			->map(function (Player $player) {
				$this->dataService->initialize($player->id);
				$this->statisticService->initialize($player->id);

				$isNew = false;

				return $this->objectService->merge([
					GeometryDashPlayerInfoObjectDefinitions::NAME->value => $player->name,
					GeometryDashPlayerInfoObjectDefinitions::ID->value => $player->id,
					GeometryDashPlayerInfoObjectDefinitions::ICON_ID->value => $player->data->icon_id,
					GeometryDashPlayerInfoObjectDefinitions::COLOR_1->value => $player->data->color1,
					GeometryDashPlayerInfoObjectDefinitions::COLOR_2->value => $player->data->color2,
					GeometryDashPlayerInfoObjectDefinitions::ICON_TYPE->value => $player->data->icon_type,
					GeometryDashPlayerInfoObjectDefinitions::SPECIAL->value => $player->data->special,
					GeometryDashPlayerInfoObjectDefinitions::UUID->value => $player->uuid,
					GeometryDashPlayerInfoObjectDefinitions::MESSAGE_STATE->value => $player->account->setting->message_state->value,
					GeometryDashPlayerInfoObjectDefinitions::HAS_NEW_FRIEND_REQUEST->value => $isNew
				], GeometryDashPlayerInfoObjectDefinitions::GLUE);
			})->join('|');
	}

	public function search(GamePlayerSearchRequest $request): string
	{
		$data = $request->validated();

		$page = 1;

		if (isset($data['page'])) {
			$page = $data['page'] + 1;
		}

		$query = Player::query()
			->where('name', 'LIKE', $data['str'] . '%');

		if (is_numeric($data['str'])) {
			$query->orWhere('id', $data['str']);
		}

		$paginate = $this->paginationService->generate($query, $page);

		if ($paginate->total <= 0) {
			return GeometryDashResponses::PLAYER_SEARCH_FAILED_EMPTY->value;
		}

		return implode('#', [
			$paginate->items->map(function (Player $player) {
				$this->dataService->initialize($player->id);
				$this->statisticService->initialize($player->id);

				return $this->objectService->merge([
					GeometryDashPlayerInfoObjectDefinitions::NAME->value => $player->name,
					GeometryDashPlayerInfoObjectDefinitions::ID->value => $player->id,
					GeometryDashPlayerInfoObjectDefinitions::STARS->value => $player->data->stars,
					GeometryDashPlayerInfoObjectDefinitions::DEMONS->value => $player->data->demons,
					GeometryDashPlayerInfoObjectDefinitions::RANKING->value => PlayerData::query()
						->where('stars', '<=', $player->data->stars)
						->count(),
					GeometryDashPlayerInfoObjectDefinitions::CREATOR_POINTS->value => $player->statistic->creator_points,
					GeometryDashPlayerInfoObjectDefinitions::ICON_ID->value => $player->data->icon_id,
					GeometryDashPlayerInfoObjectDefinitions::COLOR_1->value => $player->data->color1,
					GeometryDashPlayerInfoObjectDefinitions::COLOR_2->value => $player->data->color2,
					GeometryDashPlayerInfoObjectDefinitions::COINS->value => $player->data->coins,
					GeometryDashPlayerInfoObjectDefinitions::ICON_TYPE->value => $player->data->icon_type,
					GeometryDashPlayerInfoObjectDefinitions::SPECIAL->value => $player->data->special,
					GeometryDashPlayerInfoObjectDefinitions::UUID->value => $player->uuid,
					GeometryDashPlayerInfoObjectDefinitions::USER_COINS->value => $player->data->user_coins,
				], GeometryDashPlayerInfoObjectDefinitions::GLUE);
			})->join('|'),
			$paginate->info
		]);
	}

	public function info(GamePlayerInfoFetchRequest $request): int|string
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$targetAccount = Account::query()
			->where('id', $data['targetAccountID'])
			->first();

		$blocked = $targetAccount->blocklist()
			->where('target_account_id', $account->id)
			->exists();

		if ($blocked) {
			return GeometryDashResponses::PLAYER_INFO_FETCH_FAILED_BLOCKED->value;
		}

		$this->accountSettingService->initialize($targetAccount->id);

		$player = $this->accountService->queryAccountPlayer($targetAccount);

		$this->dataService->initialize($player->id);
		$this->statisticService->initialize($player->id);

		return $this->objectService->merge([
			GeometryDashPlayerInfoObjectDefinitions::NAME->value => $player->name,
			GeometryDashPlayerInfoObjectDefinitions::ID->value => $player->id,
			GeometryDashPlayerInfoObjectDefinitions::STARS->value => $player->data->stars,
			GeometryDashPlayerInfoObjectDefinitions::DEMONS->value => $player->data->demons,
			GeometryDashPlayerInfoObjectDefinitions::CREATOR_POINTS->value => $player->statistic->creator_points,
			GeometryDashPlayerInfoObjectDefinitions::ICON_ID->value => $player->data->icon_id,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_1->value => $player->data->color1,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_2->value => $player->data->color2,
			GeometryDashPlayerInfoObjectDefinitions::COINS->value => $player->data->coins,
			GeometryDashPlayerInfoObjectDefinitions::ICON_TYPE->value => $player->data->icon_type,
			GeometryDashPlayerInfoObjectDefinitions::SPECIAL->value => $player->data->special,
			GeometryDashPlayerInfoObjectDefinitions::UUID->value => $player->uuid,
			GeometryDashPlayerInfoObjectDefinitions::USER_COINS->value => $player->data->user_coins,
			GeometryDashPlayerInfoObjectDefinitions::MESSAGE_STATE->value => $player->account->setting->message_state->value,
			GeometryDashPlayerInfoObjectDefinitions::FRIEND_REQUEST_STATE->value => $player->account->setting->friend_request_state->value,
			GeometryDashPlayerInfoObjectDefinitions::YOUTUBE->value => $player->account->setting->youtube,
			GeometryDashPlayerInfoObjectDefinitions::CUBE_ID->value => $player->data->cube_id,
			GeometryDashPlayerInfoObjectDefinitions::SHIP_IP->value => $player->data->ship_id,
			GeometryDashPlayerInfoObjectDefinitions::BALL_ID->value => $player->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::BIRD_ID->value => $player->data->ball_id,
			GeometryDashPlayerInfoObjectDefinitions::WAVE_ID->value => $player->data->dart_id,
			GeometryDashPlayerInfoObjectDefinitions::ROBOT_ID->value => $player->data->robot_id,
			GeometryDashPlayerInfoObjectDefinitions::GLOW_ID->value => $player->data->glow_id,
			GeometryDashPlayerInfoObjectDefinitions::IS_REGISTERED->value => true,
			GeometryDashPlayerInfoObjectDefinitions::GLOBAL_RANK->value => PlayerData::query()
				->where('stars', '<=', $player->data->stars)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::FRIEND_STATE->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_ID->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_COMMENT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::IN_COMING_FRIEND_REQUEST_AGE->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::NEW_MESSAGE_COUNT->value => AccountMessage::query()
				->where('target_account_id', $targetAccount->id)
				->where('readed', false)
				->count(),
			GeometryDashPlayerInfoObjectDefinitions::NEW_FRIEND_REQUEST_COUNT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::NEW_FRIEND_COUNT->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::HAS_NEW_FRIEND_REQUEST->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::SPIDER_ID->value => $player->data->spider_id,
			GeometryDashPlayerInfoObjectDefinitions::TWITTER->value => $player->account->setting->twitter,
			GeometryDashPlayerInfoObjectDefinitions::TWITCH->value => $player->account->setting->twitch,
			GeometryDashPlayerInfoObjectDefinitions::DIAMONDS->value => $player->data->diamonds,
			GeometryDashPlayerInfoObjectDefinitions::EXPLOSION_ID->value => $player->data->explosion_id,
			GeometryDashPlayerInfoObjectDefinitions::MOD_LEVEL->value => '', // TODO
			GeometryDashPlayerInfoObjectDefinitions::COMMENT_HISTORY_STATE->value => $player->account->setting->comment_history_state->value,
			GeometryDashPlayerInfoObjectDefinitions::COLOR_3->value => $player->data->color3,
			GeometryDashPlayerInfoObjectDefinitions::MOONS->value => $player->data->moons,
			GeometryDashPlayerInfoObjectDefinitions::SWING_ID->value => $player->data->swing_id,
			GeometryDashPlayerInfoObjectDefinitions::JETPACK_ID->value => $player->data->jetpack_id,
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_DEMONS_INFO->value => collect([
				$player->statistic->completed_classic_easy_demons_count,
				$player->statistic->completed_classic_medium_demons_count,
				$player->statistic->completed_classic_hard_demons_count,
				$player->statistic->completed_classic_insane_demons_count,
				$player->statistic->completed_classic_extreme_demons_count,
				$player->statistic->completed_platformer_easy_demons_count,
				$player->statistic->completed_platformer_medium_demons_count,
				$player->statistic->completed_platformer_hard_demons_count,
				$player->statistic->completed_platformer_insane_demons_count,
				$player->statistic->completed_platformer_extreme_demons_count,
				$player->statistic->completed_weeklies_count,
				$player->statistic->completed_gauntlet_demon_levels_count
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_CLASSIC_LEVELS_INFO->value => collect([
				$player->statistic->completed_classic_auto_count,
				$player->statistic->completed_classic_easy_count,
				$player->statistic->completed_classic_normal_count,
				$player->statistic->completed_classic_hard_count,
				$player->statistic->completed_classic_harder_count,
				$player->statistic->completed_classic_insane_count,
				$player->statistic->completed_dailies_count,
				$player->statistic->completed_gauntlet_levels_count
			])->join(','),
			GeometryDashPlayerInfoObjectDefinitions::COMPLETED_PLATFORMER_LEVELS_INFO->value => collect([
				$player->statistic->completed_platformer_auto_count,
				$player->statistic->completed_platformer_easy_count,
				$player->statistic->completed_platformer_normal_count,
				$player->statistic->completed_platformer_hard_count,
				$player->statistic->completed_platformer_harder_count,
				$player->statistic->completed_platformer_insane_count
			])->join(',')
		], GeometryDashPlayerInfoObjectDefinitions::GLUE);
	}
}