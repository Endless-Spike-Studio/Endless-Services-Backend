<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\LevelList;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Objects\GameLevelListObject;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GameLevelListDeleteRequest;
use App\EndlessServer\Requests\GameLevelListListRequest;
use App\EndlessServer\Requests\GameLevelListUploadRequest;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashLevelListSearchTypes;
use App\GeometryDash\Enums\GeometryDashLevelListUnlistedTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\Objects\GeometryDashLevelListObjectDefinitions;
use Base64Url\Base64Url;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelListController
{
	public function __construct(
		protected AccountFriendRepository $accountFriendRepository,
		protected GamePaginationService   $paginationService
	)
	{

	}

	public function upload(GameLevelListUploadRequest $request)
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		if (!isset($data['listVersion'])) {
			$data['listVersion'] = 1;
		}

		if (!isset($data['original'])) {
			$data['original'] = null;
		}

		$description = null;

		if ($data['listDesc'] !== null) {
			$description = Base64Url::decode($data['listDesc']);
		}

		$attributes = [
			'account_id' => $account->id,
			'name' => $data['listName'],
			'description' => $description,
			'difficulty' => $data['difficulty'],
			'version' => $data['listVersion'],
			'original_level_list_id' => $data['original'],
			'unlisted_type' => $data['unlisted']
		];

		if (isset($data['listID'])) {
			$list = LevelList::query()
				->where('account_id', $account->id)
				->where('id', $data['listID'])
				->first();

			if ($list === null) {
				return GeometryDashResponses::LEVEL_LIST_UPLOAD_FAILED_NOT_FOUND->value;
			}

			unset($attributes['account_id']);

			$list->update($attributes);
		} else {
			$list = LevelList::query()
				->create($attributes);
		}

		$list->levels()
			->delete();

		foreach (explode(',', $data['listLevels']) as $index => $levelId) {
			$list->levels()
				->create([
					'level_id' => $levelId,
					'index' => $index + 1
				]);
		}

		return $list->id;
	}

	public function delete(GameLevelListDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		LevelList::query()
			->where('account_id', $account->id)
			->where('id', $data['listID'])
			->delete();

		return GeometryDashResponses::LEVEL_LIST_DELETE_SUCCESS->value;
	}

	public function list(GameLevelListListRequest $request): int|string
	{
		$data = $request->validated();

		$query = LevelList::query();

		if ($data['type'] !== GeometryDashLevelListSearchTypes::SEARCH->value) {
			$query->where('unlisted_type', GeometryDashLevelListUnlistedTypes::PUBLIC->value);
		}

		switch ($data['type']) {
			case GeometryDashLevelListSearchTypes::SEARCH->value:
				if (is_numeric($data['str'])) {
					$list = LevelList::query()
						->where('id', $data['str'])
						->first();

					if ($list === null) {
						return GeometryDashResponses::LEVEL_LIST_SEARCH_FAILED_NOT_FOUND->value;
					}

					switch ($list->unlisted_type) {
						case GeometryDashLevelListUnlistedTypes::PUBLIC->value:
							$query->where('id', $list->id);
							break;
						case GeometryDashLevelListUnlistedTypes::FRIENDS_ONLY->value:
							/** @var ?Account $account */
							$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

							if ($account === null) {
								return GeometryDashResponses::LEVEL_SEARCH_FAILED_NO_LOGIN->value;
							}

							$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id);

							$query->where('id', $list->id);
							$query->whereIn('account_id', $friendAccountIDs);
							break;
						case GeometryDashLevelListUnlistedTypes::SELF_ONLY->value:
							$query->whereNot('id', $list->id);
							break;
					}
				} else {
					$query->where('name', 'LIKE', $data['str'] . '%');
				}
				break;
			case GeometryDashLevelListSearchTypes::MOST_DOWNLOADED->value:
				$query->withCount('downloadRecords');
				$query->orderByDesc('download_records_count');
				break;
			case GeometryDashLevelListSearchTypes::MOST_LIKED->value:
				$query->withCount('likeRecords');
				$query->orderByDesc('like_records_count');
				break;
			case GeometryDashLevelListSearchTypes::TRENDING->value:
				$query->withCount('likeRecords');
				$query->orderByDesc('like_records_count');
				$query->where('created_at', '>=', now()->subDays(7));
				break;
			case GeometryDashLevelListSearchTypes::RECENT->value:
				$query->latest();
				break;
			case GeometryDashLevelListSearchTypes::LIST_PLAYER->value:
				$query->where('account_id', $data['str']);
				break;
			case GeometryDashLevelListSearchTypes::AWARDED->value:
				$query->whereHas('rating', function (Builder $query) {
					$query->where('diamonds', '>', 0);
				});
				break;
			case GeometryDashLevelListSearchTypes::FOLLOWED->value:
				$followedAccountIds = explode(',', $data['followed']);
				$query->whereIn('account_id', $followedAccountIds);
				break;
			case GeometryDashLevelListSearchTypes::FRIENDS->value:
				/** @var ?Account $account */
				$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

				if ($account === null) {
					return GeometryDashResponses::LEVEL_LIST_LIST_FAILED_NO_LOGIN->value;
				}

				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id);

				$query->whereIn('account_id', $friendAccountIDs);
				break;
			case GeometryDashLevelListSearchTypes::MOD_SENT->value:
				// TODO
				break;
		}

		$paginate = $this->paginationService->generate($query, $data['page']);

		return implode('#', [
			$paginate->items->map(function (LevelList $list) {
				return new GameLevelListObject($list)->merge();
			})->join(GeometryDashLevelListObjectDefinitions::SEPARATOR),
			$paginate->items->map(function (LevelList $list) {
				return $list->account->player;
			})->unique(function (Player $player) {
				return $player->id;
			})->map(function (Player $player) {
				return implode(':', [
					$player->id,
					$player->name,
					$player->uuid
				]);
			})->join('|'),
			$paginate->info(),
			config('app.name')
		]);
	}
}