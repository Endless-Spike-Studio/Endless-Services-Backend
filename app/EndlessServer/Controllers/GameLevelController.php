<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Objects\GameSongObject;
use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Level;
use App\EndlessServer\Models\LevelDaily;
use App\EndlessServer\Models\LevelEvent;
use App\EndlessServer\Models\LevelGauntlet;
use App\EndlessServer\Models\LevelSongMapping;
use App\EndlessServer\Models\LevelWeekly;
use App\EndlessServer\Models\Player;
use App\EndlessServer\Objects\GameLevelObject;
use App\EndlessServer\Repositories\AccountFriendRepository;
use App\EndlessServer\Requests\GameLevelDeleteRequest;
use App\EndlessServer\Requests\GameLevelDownloadRequest;
use App\EndlessServer\Requests\GameLevelReportRequest;
use App\EndlessServer\Requests\GameLevelSearchRequest;
use App\EndlessServer\Requests\GameLevelUpdateDescriptionRequest;
use App\EndlessServer\Requests\GameLevelUploadRequest;
use App\EndlessServer\Requests\GameSpecialLevelFetchRequest;
use App\EndlessServer\Services\GameLevelDataStorageService;
use App\EndlessServer\Services\GamePaginationService;
use App\GeometryDash\Enums\GeometryDashLevelRatingDemonDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelRatingEpicTypes;
use App\GeometryDash\Enums\GeometryDashLevelSearchDifficulties;
use App\GeometryDash\Enums\GeometryDashLevelSearchTypes;
use App\GeometryDash\Enums\GeometryDashResponses;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\GeometryDashSpecialLevelIds;
use App\GeometryDash\Enums\GeometryDashSpecialLevelTypes;
use App\GeometryDash\Enums\Objects\GeometryDashLevelObjectDefinitions;
use App\GeometryDash\GeometryDashLevelSearchDemonFilters;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use App\GeometryDash\Services\GeometryDashObjectService;
use Base64Url\Base64Url;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

readonly class GameLevelController
{
	public function __construct(
		protected GameLevelDataStorageService  $storageService,
		protected GamePaginationService        $paginationService,
		protected AccountFriendRepository      $accountFriendRepository,
		protected GeometryDashAlgorithmService $algorithmService,
		protected GeometryDashObjectService    $objectService
	)
	{

	}

	public function upload(GameLevelUploadRequest $request)
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		if (!isset($data['original'])) {
			$data['original'] = 0;
		}

		if (!isset($data['extraString'])) {
			$data['extraString'] = '';
		}

		$description = null;

		if ($data['levelDesc'] !== null) {
			$description = Base64Url::decode($data['levelDesc']);
		}

		$attributes = [
			'player_id' => $player->id,
			'game_version' => $data['gameVersion'],
			'name' => $data['levelName'],
			'description' => $description,
			'version' => $data['levelVersion'],
			'length' => $data['levelLength'],
			'audio_track_id' => $data['audioTrack'],
			'password' => $data['password'],
			'original_level_id' => $data['original'],
			'two_player_mode_enabled' => $data['twoPlayer'],
			'objects' => $data['objects'],
			'coins' => $data['coins'],
			'requested_stars' => $data['requestedStars'],
			'unlisted_type' => $data['unlisted'],
			'ldm_enabled' => $data['ldm'],
			'editor_time' => $data['wt'],
			'previous_editor_time' => $data['wt2'],
			'extra' => $data['extraString'],
			'replay' => $data['levelInfo'],
			'verification_time' => $data['ts']
		];

		if (isset($data['levelID'])) {
			$level = Level::query()
				->where('player_id', $player->id)
				->where('id', $data['levelID'])
				->first();

			if ($level === null) {
				return GeometryDashResponses::LEVEL_UPLOAD_FAILED_NOT_FOUND->value;
			}

			unset($attributes['player_id']);

			$level->update($attributes);
		} else {
			$level = Level::query()
				->create($attributes);
		}

		$this->storageService->level = $level;
		$this->storageService->store($data['levelString']);

		return $level->id;
	}

	public function getSpecial(GameSpecialLevelFetchRequest $request): int|string
	{
		$data = $request->validated();

		$offset = 0;

		$special = null;

		$now = now();

		switch ($data['type']) {
			case GeometryDashSpecialLevelTypes::DAILY->value:
				$special = LevelDaily::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();
				break;
			case GeometryDashSpecialLevelTypes::WEEKLY->value:
				$offset = 100000;

				$special = LevelWeekly::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();
				break;
			case GeometryDashSpecialLevelTypes::EVENT->value:
				$offset = 200000;

				$special = LevelEvent::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();
				break;
		}

		if ($special === null) {
			return GeometryDashResponses::SPECIAL_LEVEL_NOT_FOUND->value;
		}

		return implode('|', [
			$offset + $special->id,
			$now->diffInSeconds($special->expired_at)
		]);
	}

	public function report(GameLevelReportRequest $request): int
	{
		$data = $request->validated();

		$level = Level::query()
			->where('id', $data['levelID'])
			->first();

		$level->reports()
			->create();

		return GeometryDashResponses::LEVEL_REPORT_SUCCESS->value;
	}

	public function download(GameLevelDownloadRequest $request): string
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$special = null;

		switch ($data['levelID']) {
			case GeometryDashSpecialLevelIds::DAILY->value:
				$now = now();

				$daily = LevelDaily::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();

				if ($daily === null) {
					return GeometryDashResponses::SPECIAL_LEVEL_NOT_FOUND->value;
				}

				$special = $daily;

				$level = Level::query()
					->where('id', $daily->level_id)
					->first();
				break;
			case GeometryDashSpecialLevelIds::WEEKLY->value:
				$now = now();

				$weekly = LevelWeekly::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();

				if ($weekly === null) {
					return GeometryDashResponses::SPECIAL_LEVEL_NOT_FOUND->value;
				}

				$special = $weekly;

				$level = Level::query()
					->where('id', $weekly->level_id)
					->first();
				break;
			case GeometryDashSpecialLevelIds::EVENT->value:
				$now = now();

				$event = LevelEvent::query()
					->latest()
					->whereNull('expired_at')
					->orWhere('expired_at', '>', $now)
					->first();

				if ($event === null) {
					return GeometryDashResponses::SPECIAL_LEVEL_NOT_FOUND->value;
				}

				$special = $event;

				$level = Level::query()
					->where('id', $event->level_id)
					->first();
				break;
			default:
				$level = Level::query()
					->where('id', $data['levelID'])
					->first();
				break;
		}

		if ($level === null) {
			return GeometryDashResponses::LEVEL_NOT_FOUND->value;
		}

		$level->downloadRecords()
			->updateOrCreate([
				'player_id' => $player->id
			]);

		$levelObjectString = new GameLevelObject($level, $special)->merge();
		$levelObject = $this->objectService->split($levelObjectString, GeometryDashLevelObjectDefinitions::GLUE);

		return implode('#', [
			$levelObjectString,
			$this->algorithmService->generateLevelDivided($levelObject[GeometryDashLevelObjectDefinitions::DATA->value], 40, 39),
			sha1(
				implode(',', [
					$level->player_id,
					$level->rating->stars,
					(int)($level->rating->difficulty === GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON->value && $level->rating->demon_difficulty !== null),
					$level->id,
					(int)$level->rating->coin_verified,
					$level->rating->featured_score,
					$level->password,
					$special !== null ? $special->id : 0
				]) .
				GeometryDashSalts::LEVEL->value
			),
			$special !== null ? implode(':', [
				$level->player->id,
				$level->player->name,
				$level->player->uuid
			]) : config('app.name')
		]);
	}

	public function updateDescription(GameLevelUpdateDescriptionRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		$level = Level::query()
			->where('player_id', $player->id)
			->where('id', $data['levelID'])
			->first();

		if ($level === null) {
			return GeometryDashResponses::LEVEL_UPLOAD_DESCRIPTION_FAILED_LEVEL_NOT_FOUND->value;
		}

		$description = null;

		if ($data['levelDesc'] !== null) {
			$description = Base64Url::decode($data['levelDesc']);
		}

		$level->update([
			'description' => $description
		]);

		return GeometryDashResponses::LEVEL_UPLOAD_DESCRIPTION_SUCCESS->value;
	}

	public function search(GameLevelSearchRequest $request): string
	{
		$data = $request->validated();

		$page = 1;

		if (isset($data['page'])) {
			$page = $data['page'];
		}

		$query = Level::query();

		if (isset($data['gauntlet'])) {
			$gauntlet = LevelGauntlet::query()
				->where('gauntlet_id', $data['gauntlet'])
				->first();

			$levels = [
				$gauntlet->level1_id,
				$gauntlet->level2_id,
				$gauntlet->level3_id,
				$gauntlet->level4_id,
				$gauntlet->level5_id
			];

			$query->whereIn('id', $levels);

			goto result;
		}

		switch ($data['type']) {
			case GeometryDashLevelSearchTypes::SEARCH->value:
				$query->where('name', 'LIKE', $data['str'] . '%');
				break;
			case GeometryDashLevelSearchTypes::MOST_DOWNLOADED->value:
				$query->withCount('downloadRecords');
				$query->orderByDesc('download_records_count');
				break;
			case GeometryDashLevelSearchTypes::MOST_LIKED->value:
				// TODO
				break;
			case GeometryDashLevelSearchTypes::TRENDING->value:
				// TODO: order by likes desc
				$query->where('created_at', '>=', now()->subDays(7));
				break;
			case GeometryDashLevelSearchTypes::RECENT->value:
				$query->latest();
				break;
			case GeometryDashLevelSearchTypes::LIST_PLAYER->value:
				/** @var Player $player */
				$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

				if (isset($data['local'])) {
					$query->where('player_id', $player->id);
				} else {
					$query->where('player_id', $data['str']);
				}
				break;
			case GeometryDashLevelSearchTypes::FEATURED->value:
				$query->whereHas('rating', function (Builder $query) {
					$query->where('featured_score', '>', 0);
				});
				break;
			case GeometryDashLevelSearchTypes::MAGIC->value:
				$query->where('objects', '>=', 65535);
				break;
			case GeometryDashLevelSearchTypes::MOD_SENT->value:
				// TODO
				break;
			case GeometryDashLevelSearchTypes::LEVEL_LIST->value:
				$levelIds = explode(',', $data['str']);
				$query->whereIn('id', $levelIds);
				break;
			case GeometryDashLevelSearchTypes::AWARDED->value:
				$query->whereHas('rating', function (Builder $query) {
					$query->where('stars', '>', 0);
				});
				break;
			case GeometryDashLevelSearchTypes::FOLLOWED->value:
				$followedPlayerIds = explode(',', $data['followed']);
				$query->whereIn('player_id', $followedPlayerIds);
				break;
			case GeometryDashLevelSearchTypes::FRIENDS->value:
				/** @var ?Account $account */
				$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

				if ($account === null) {
					return GeometryDashResponses::LEVEL_SEARCH_FAILED_NO_LOGIN->value;
				}

				$friendAccountIDs = $this->accountFriendRepository->queryIdsByAccountId($account->id);

				$friendPlayerIDs = Account::query()
					->whereIn('id', $friendAccountIDs)
					->get()
					->map(function (Account $account) {
						return $account->player->id;
					});

				$query->whereIn('player_id', $friendPlayerIDs);
				break;
			case GeometryDashLevelSearchTypes::HALL_OF_FAME->value:
				$query->whereHas('rating', function (Builder $query) {
					$query->where('epic_type', '!=', GeometryDashLevelRatingEpicTypes::NONE->value);
				});
				break;
			case GeometryDashLevelSearchTypes::DAILY_HISTORY->value:
				// TODO
				break;
			case GeometryDashLevelSearchTypes::WEEKLY_HISTORY->value:
				// TODO
				break;
			case GeometryDashLevelSearchTypes::LIST_LEVELS->value:
				// TODO
				break;
		}

		if (isset($data['diff'])) {
			switch ($data['diff']) {
				case GeometryDashLevelSearchDifficulties::NA->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::NA->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::AUTO->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON->value);
						$query->whereNull('demon_difficulty');
					});
					break;
				case GeometryDashLevelSearchDifficulties::EASY->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::EASY->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::NORMAL->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::NORMAL->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::HARD->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::HARD->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::HARDER->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::HARDER->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::INSANE->value:
					$query->whereHas('rating', function (Builder $query) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::INSANE->value);
					});
					break;
				case GeometryDashLevelSearchDifficulties::DEMON->value:
					$query->whereHas('rating', function (Builder $query) use ($data) {
						$query->where('difficulty', GeometryDashLevelRatingDifficulties::AUTO_OR_DEMON->value);
						$query->whereNotNull('demon_difficulty');

						if (isset($data['demon_filter'])) {
							switch ($data['demon_filter']) {
								case GeometryDashLevelSearchDemonFilters::EASY_DEMON->value:
									$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EASY->value);
									break;
								case GeometryDashLevelSearchDemonFilters::MEDIUM_DEMON->value:
									$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::MEDIUM->value);
									break;
								case GeometryDashLevelSearchDemonFilters::HARD_DEMON->value:
									$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::HARD->value);
									break;
								case GeometryDashLevelSearchDemonFilters::INSANE_DEMON->value:
									$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::INSANE->value);
									break;
								case GeometryDashLevelSearchDemonFilters::EXTREME_DEMON->value:
									$query->where('demon_difficulty', GeometryDashLevelRatingDemonDifficulties::EXTREME->value);
									break;
							}
						}
					});
					break;
			}
		}

		if (isset($data['len'])) {
			$query->where('length', $data['len']);
		}

		if (isset($data['star']) && $data['star'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('stars', '>', 0);
			});
		}

		if (isset($data['uncompleted']) && $data['uncompleted'] > 0) {
			$completedLevelIds = $data['completedLevels'];
			$query->whereNotIn('id', $completedLevelIds);
		}

		if (isset($data['onlyCompleted']) && $data['onlyCompleted'] > 0) {
			$completedLevelIds = $data['completedLevels'];
			$query->whereIn('id', $completedLevelIds);
		}

		if (isset($data['original']) && $data['original'] > 0) {
			$query->whereNotNull('original_level_id');
		}

		if (isset($data['coins']) && $data['coins'] > 0) {
			$query->where('coins', '>', 0);
		}

		if (isset($data['twoPlayer']) && $data['twoPlayer'] > 0) {
			$query->where('two_player_mode_enabled', true);
		}

		if (isset($data['song'])) {
			if (isset($data['customSong']) && $data['customSong'] > 0) {
				$query->whereHas('songMappings', function (Builder $query) use ($data) {
					$query->where('newground_song_id', $data['song']);
				});
			} else {
				$query->where('audio_track_id', $data['song']);
			}
		}

		if (isset($data['noStar']) && $data['noStar'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('stars', '<=', 0);
			});
		}

		if (isset($data['featured']) && $data['featured'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('featured_score', '>', 0);
			});
		}

		if (isset($data['epic']) && $data['epic'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('epic_type', GeometryDashLevelRatingEpicTypes::EPIC->value);
			});
		}

		if (isset($data['legendary']) && $data['legendary'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('epic_type', GeometryDashLevelRatingEpicTypes::LEGENDARY->value);
			});
		}

		if (isset($data['mythic']) && $data['mythic'] > 0) {
			$query->whereHas('rating', function (Builder $query) {
				$query->where('epic_type', GeometryDashLevelRatingEpicTypes::MYTHIC->value);
			});
		}

		result:

		$paginate = $this->paginationService->generate($query, $page);

		return implode('#', [
			$paginate->items->map(function (Level $level) {
				return new GameLevelObject($level)->except([
					GeometryDashLevelObjectDefinitions::DATA->value
				])->merge();
			})->join(GeometryDashLevelObjectDefinitions::SEPARATOR),
			$paginate->items->map(function (Level $level) {
				return $level->player;
			})->unique(function (Player $player) {
				return $player->id;
			})->map(function (Player $player) {
				return implode(':', [
					$player->id,
					$player->name,
					$player->uuid
				]);
			})->join('|'),
			$paginate->items->map(function (Level $level) {
				return $level->songMappings;
			})->flatten()
				->unique(function (LevelSongMapping $mapping) {
					return $mapping->newgrounds_song_id;
				})
				->map(function (LevelSongMapping $mapping) {
					return new GameSongObject($mapping->newgroundsSong);
				})->join(':'),
			$paginate->info(),
			sha1(
				$paginate->items->map(function (Level $level) {
					return implode('', [
						substr($level->id, 0, 1),
						substr($level->id, -1),
						$level->rating->stars,
						$level->coins->value,
						$level->rating->coin_verified
					]);
				})->join('') .
				GeometryDashSalts::LEVEL->value
			)
		]);
	}

	public function delete(GameLevelDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Player $player */
		$player = Auth::guard(EndlessServerAuthenticationGuards::PLAYER->value)->user();

		Level::query()
			->where('player_id', $player->id)
			->where('id', $data['levelID'])
			->delete();

		return GeometryDashResponses::LEVEL_DELETE_SUCCESS->value;
	}
}