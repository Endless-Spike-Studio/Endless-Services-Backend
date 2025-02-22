<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountBlocklist;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GamePlayerInfoFetchRequest;
use App\EndlessServer\Requests\GamePlayerListRequest;
use App\EndlessServer\Requests\GamePlayerSearchRequest;
use App\EndlessServer\Responses\GamePlayerInfoObjectResponse;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GameAccountSettingService;
use App\EndlessServer\Services\GamePaginationService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashPlayerListTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Services\GeometryDashObjectService;
use Illuminate\Support\Carbon;
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

				$friendAccountPlayerIDs = Account::query()
					->whereIn('id', $friendAccountIDs)
					->get()
					->map(function (Account $account) {
						return $account->player->id;
					});

				$query->whereIn('id', $friendAccountPlayerIDs);
				break;
			case GeometryDashPlayerListTypes::BLOCKLIST->value:
				$blockAccountIDs = AccountBlocklist::query()
					->where('account_id', $account->id)
					->pluck('target_account_id');

				$blockAccountPlayerIDs = Account::query()
					->whereIn('id', $blockAccountIDs)
					->get()
					->map(function (Account $account) {
						return $account->player->id;
					});

				$query->whereIn('id', $blockAccountPlayerIDs);
				break;
			default:
				return GeometryDashResponses::PLAYER_LIST_FAILED_INVALID_TYPE->value;
		}

		if ($query->count() <= 0) {
			return GeometryDashResponses::PLAYER_LIST_FAILED_EMPTY->value;
		}

		return $query->get()
			->map(function (Player $player) use ($request) {
				return new GamePlayerInfoObjectResponse($player)->toResponse($request);
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
			$paginate->items->map(function (Player $player) use ($request) {
				return new GamePlayerInfoObjectResponse($player)->toResponse($request);
			})->join('|'),
			$paginate->info
		]);
	}

	public function info(GamePlayerInfoFetchRequest $request): int|string
	{
		$data = $request->validated();

		Carbon::setLocale('en');

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$targetAccount = Account::query()
			->where('id', $data['targetAccountID'])
			->first();

		return new GamePlayerInfoObjectResponse($targetAccount->player, $account->player)->toResponse($request);
	}
}