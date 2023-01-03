<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Enums\GDCS\Game\LevelRatingDemonDifficulty;
use App\Enums\GDCS\Game\Objects\LevelObject;
use App\Enums\GDCS\Game\Parameters\LevelSearchDiff;
use App\Enums\GDCS\Game\Parameters\LevelSearchType;
use App\Enums\GDCS\Game\SpecialLevelID;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\DailyOrWeeklyLevelFetchRequest;
use App\Http\Requests\GDCS\Game\LevelDeleteRequest;
use App\Http\Requests\GDCS\Game\LevelDescUpdateRequest;
use App\Http\Requests\GDCS\Game\LevelDownloadRequest;
use App\Http\Requests\GDCS\Game\LevelSearchRequest;
use App\Http\Requests\GDCS\Game\LevelUploadRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelDownloadRecord;
use App\Models\GDCS\LevelGauntlet;
use App\Models\GDCS\WeeklyLevel;
use App\Services\Game\AlgorithmService;
use App\Services\Game\BaseGameService;
use App\Services\Game\CustomSongService;
use App\Services\Game\ObjectService;
use App\Services\NGProxy\SongService;
use App\Services\Storage\GameLevelDataStorageService;
use Carbon\Carbon;

class LevelController extends Controller
{
    use GameLog;

    public function upload(LevelUploadRequest $request)
    {
        $data = $request->validated();

        $level = Level::query()
            ->updateOrCreate([
                'id' => $data['levelID'] ?? 0,
                'name' => $data['levelName'],
                'user_id' => $request->user->id,
            ], [
                'game_version' => $data['gameVersion'],
                'user_id' => $request->user->id,
                'desc' => $data['levelDesc'],
                'version' => $data['levelVersion'],
                'length' => $data['levelLength'],
                'audio_track' => $data['audioTrack'],
                'song_id' => $data['songID'],
                'auto' => $data['auto'],
                'password' => $data['password'],
                'original_level_id' => $data['original'] ?? 0,
                'two_player' => $data['twoPlayer'],
                'objects' => $data['objects'],
                'coins' => $data['coins'],
                'requested_stars' => $data['requestedStars'],
                'unlisted' => $data['unlisted'],
                'ldm' => $data['ldm'],
                'extra_string' => $data['extraString'],
                'level_info' => $data['levelInfo'],
            ]);

        $level->data = $data['levelString'];
        $this->logGame(__('gdcn.game.action.level_upload_success'));

        return $level->id;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function search(LevelSearchRequest $request): int|string
    {
        $data = $request->validated();
        $type = (int)$data['type'];

        $query = Level::query()
            ->with('user');

        $showUnlisted = false;
        if (!empty($data['gauntlet'])) {
            $gauntlet = LevelGauntlet::query()
                ->find($data['gauntlet']);

            if (!$gauntlet) {
                throw new GeometryDashChineseServerException(__('gdcn.game.error.level_search_failed_gauntlet_not_found'), game_response: Response::GAME_LEVEL_SEARCH_FAILED_GAUNTLET_NOT_FOUND->value);
            }

            $type = LevelSearchType::LIST;
            $data['str'] = implode(',', $gauntlet->levels);
        }

        switch ($type) {
            case LevelSearchType::SEARCH:
                $query->whereKey($data['str']);
                $query->orWhere('name', 'LIKE', $data['str'] . '%');
                $query->orderByDesc('likes');

                if (is_numeric($data['str'])) {
                    $showUnlisted = true;
                }
                break;
            case LevelSearchType::MOST_DOWNLOADED:
                $query->orderByDesc('downloads');
                break;
            case LevelSearchType::MOST_LIKED:
                $query->orderByDesc('likes');
                break;
            case LevelSearchType::TRENDING:
                $query->where('created_at', '>=', now()->subDays(7));
                $query->orderByDesc('likes');
                break;
            case LevelSearchType::RECENT:
                $query->orderByDesc('created_at');
                break;
            case LevelSearchType::USER:
                $query->where('user_id', $data['str']);
                $showUnlisted = true;
                break;
            case LevelSearchType::FEATURED:
            case LevelSearchType::ALSO_FEATURED:
                $query->whereHas('rating', function ($query) {
                    $query->where('featured_score', '>', 0);
                });
                break;
            case LevelSearchType::MAGIC:
                $query->where('objects', '>', 9999);
                break;
            case LevelSearchType::LIST:
                $query->whereKey($data['str']);
                break;
            case LevelSearchType::AWARDED:
                $query->whereHas('rating', function ($query) {
                    $query->orderByDesc('created_at');
                });
                break;
            case LevelSearchType::FOLLOWED:
                $query->whereIn('user_id', $data['followed']);
                break;
            case LevelSearchType::FRIENDS:
                if (!$request->auth()) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.level_search_failed_authorization_exception'), game_response: Response::GAME_LEVEL_SEARCH_FAILED_AUTHORIZATION_EXCEPTION->value);
                }

                $query->whereIn('user_id', $request->account->friend_user_ids_with_self);
                break;
            case LevelSearchType::HALL_OF_FAME:
                $query->whereHas('rating', function ($query) {
                    $query->where('epic', true);
                });
                break;
            case LevelSearchType::MOD_SENT:
            case LevelSearchType::WORLD_MOST_LIKED:
            case LevelSearchType::UNKNOWN:
            default:
                throw new GeometryDashChineseServerException(__('gdcn.game.error.level_search_failed_unsupported_type'), game_response: Response::GAME_LEVEL_SEARCH_FAILED_UNSUPPORTED_TYPE->value);
        }

        if (!$showUnlisted) {
            $query->whereNot('unlisted', true);
        }

        if (!empty($data['diff'])) {
            $diff = (int)$data['diff'];
            if ($diff === LevelSearchDiff::DEMON && !empty($data['demonFilter'])) {
                $data['diff'] = $data['demonFilter'] + 5;
            }

            switch ($data['diff']) {
                case LevelSearchDiff::NA:
                    $query->whereDoesntHave('rating');
                    break;
                case LevelSearchDiff::DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                    });
                    break;
                case LevelSearchDiff::AUTO:
                    $query->whereHas('rating', function ($query) {
                        $query->where('auto', true);
                    });
                    break;
                case LevelSearchDiff::EASY_DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                        $query->where('demon_difficulty', LevelRatingDemonDifficulty::EASY);
                    });
                    break;
                case LevelSearchDiff::MEDIUM_DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                        $query->where('demon_difficulty', LevelRatingDemonDifficulty::MEDIUM);
                    });
                    break;
                case LevelSearchDiff::HARD_DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                        $query->whereIn('demon_difficulty', [LevelRatingDemonDifficulty::NA, LevelRatingDemonDifficulty::HARD]);
                    });
                    break;
                case LevelSearchDiff::INSANE_DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                        $query->where('demon_difficulty', LevelRatingDemonDifficulty::INSANE);
                    });
                    break;
                case LevelSearchDiff::EXTREME_DEMON:
                    $query->whereHas('rating', function ($query) {
                        $query->where('demon', true);
                        $query->where('demon_difficulty', LevelRatingDemonDifficulty::EXTREME);
                    });
                    break;
                default:
                    $query->whereHas('rating', function ($query) use ($data) {
                        $query->whereIn('difficulty', explode(',', str_replace(',', '0,', $data['diff']) . '0'));
                    });
                    break;
            }
        }

        if (!empty($data['len'])) {
            $query->whereIn('length', $data['len']);
        }

        if (!empty($data['uncompleted'])) {
            $query->whereKeyNot($data['completedLevels']);
        }

        if (!empty($data['onlyCompleted'])) {
            $query->whereKey($data['completedLevels']);
        }

        if (!empty($data['featured'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('featured_score', '>', 0);
            });
        }

        if (!empty($data['original'])) {
            $query->whereNot('original_level_id', 0);
        }

        if (!empty($data['twoPlayer'])) {
            $query->where('two_player', true);
        }

        if (!empty($data['coins'])) {
            $query->where('coins', '>', 0);
        }

        if (!empty($data['epic'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('epic', true);
            });
        }

        if (!empty($data['noStar'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('stars', 0);
            });
        }

        if (!empty($data['star'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('stars', '>', 0);
            });
        }

        if (!empty($data['song'])) {
            $query->where(!empty($data['customSong']) ? 'song_id' : 'audio_track', $data['song']);
        }

        $count = $query->count();
        if ($count <= 0) {
            throw new GeometryDashChineseServerException(
                __('gdcn.game.error.level_search_failed_empty'),
                game_response: '##' . Response::empty() . '#' . sha1(Salts::LEVEL->value)
            );
        }

        $users = [];
        $songs = [];
        $hashes = [];

        $this->logGame(__('gdcn.game.action.level_search_success'));

        return implode('#', [
            $query->forPage(++$data['page'], BaseGameService::$perPage)
                ->get()
                ->map(function (Level $level) use ($data, &$users, &$songs, &$hashes) {
                    $levelInfo = [
                        LevelObject::ID => $level->id,
                        LevelObject::NAME => $level->name,
                        LevelObject::DESCRIPTION => $level->desc,
                        LevelObject::VERSION => $level->version,
                        LevelObject::USER_ID => $level->user_id,
                        LevelObject::IS_RATED => $level->rating->stars > 0 ? 10 : 0,
                        LevelObject::DIFFICULTY => $level->rating->difficulty->value,
                        LevelObject::DOWNLOADS => $level->downloads,
                        LevelObject::AUDIO_TRACK => $level->audio_track,
                        LevelObject::GAME_VERSION => $level->game_version,
                        LevelObject::LIKES => $level->likes,
                        LevelObject::LENGTH => $level->length,
                        LevelObject::IS_DEMON => $level->rating->demon,
                        LevelObject::STARS => $level->rating->stars,
                        LevelObject::FEATURED_SCORE => $level->rating->featured_score,
                        LevelObject::IS_AUTO => $level->rating->auto,
                        LevelObject::ORIGINAL_LEVEL_ID => $level->original_level_id,
                        LevelObject::IS_TWO_PLAYER => $level->two_player,
                        LevelObject::SONG_ID => $level->song_id,
                        LevelObject::COINS => $level->coins,
                        LevelObject::IS_COIN_VERIFIED => $level->rating->coin_verified,
                        LevelObject::REQUESTED_STARS => $level->requested_stars,
                        LevelObject::IS_EPIC => $level->rating->epic,
                        LevelObject::DEMON_DIFFICULTY => $level->rating->demon_difficulty->value,
                        LevelObject::OBJECTS => min($level->objects, 65535),
                    ];

                    if (!empty($data['gauntlet'])) {
                        $levelInfo[LevelObject::GAUNTLET_ID] = $data['gauntlet'];
                    }

                    if (!array_key_exists($level->user->id, $users)) {
                        $users[$level->user->id] = implode(':', [
                            $level->user->id,
                            $level->user->name,
                            $level->user->uuid,
                        ]);
                    }

                    if ($level->song_id > 0 && !array_key_exists($level->song_id, $songs)) {
                        if ($level->song_id >= CustomSongService::$offset) {
                            $customSong = CustomSong::query()
                                ->find($level->song_id - CustomSongService::$offset);

                            if (!empty($customSong)) {
                                $songs[$level->song_id] = $customSong->object;
                            }
                        } else {
                            $songs[$level->song_id] = app(SongService::class)->find($level->song_id, true)->object;
                        }
                    }

                    $hashes[] = implode(null, [
                        substr($level->id, 0, 1),
                        substr($level->id, -1, 1),
                        $level->rating->stars,
                        (int)$level->rating->coin_verified,
                    ]);

                    return ObjectService::merge($levelInfo, ':');
                })->join('|'),
            implode('|', $users),
            implode('~:~', $songs),
            AlgorithmService::genPage($data['page'], $count),
            sha1(implode(null, $hashes) . Salts::LEVEL->value),
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function download(LevelDownloadRequest $request): int|string
    {
        $data = $request->validated();

        $now = Carbon::now();
        switch ($data['levelID']) {
            case SpecialLevelID::DAILY->value:
                $item = DailyLevel::query()
                    ->where('apply_at', '<=', $now)
                    ->orderBy('apply_at', 'desc')
                    ->first();

                if (!$item) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.level_download_failed_daily_not_found'), game_response: Response::GAME_LEVEL_DOWNLOAD_FAILED_DAILY_NOT_FOUND->value);
                }

                $level = $item->level;
                $specialID = $item->id;
                break;
            case SpecialLevelID::WEEKLY->value:
                $item = WeeklyLevel::query()
                    ->where('apply_at', '<=', $now)
                    ->orderBy('apply_at', 'desc')
                    ->first();

                if (!$item) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.level_download_failed_weekly_not_found'), game_response: Response::GAME_LEVEL_DOWNLOAD_FAILED_WEEKLY_NOT_FOUND->value);
                }

                $level = $item->level;
                $specialID = $item->id;
                break;
            default:
                $level = Level::query()
                    ->find($data['levelID']);

                if (!$level) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.level_download_failed_not_found'), game_response: Response::GAME_LEVEL_DOWNLOAD_FAILED_NOT_FOUND->value);
                }

                $specialID = 0;
                break;
        }

        $hash = implode(',', [
            $level->user_id,
            $level->rating->stars,
            (int)$level->rating->demon,
            $level->id,
            (int)$level->rating->coin_verified,
            $level->rating->featured_score,
            $level->password,
            $specialID,
        ]);

        $request->auth();
        $record = LevelDownloadRecord::query()
            ->firstOrCreate([
                'level_id' => $level->id,
                'ip' => $request->ip(),
            ], [
                'user_id' => $request->user->id ?? 0
            ]);

        $levelString = $level->data;
        if ($record->wasRecentlyCreated) {
            $level->downloads++;
            $level->save();
        }

        $this->logGame(__('gdcn.game.action.level_download_success'));

        return implode('#', [
            ObjectService::merge([
                LevelObject::ID => $level->id,
                LevelObject::NAME => $level->name,
                LevelObject::DESCRIPTION => $level->desc,
                LevelObject::DATA => $levelString,
                LevelObject::VERSION => $level->version,
                LevelObject::USER_ID => $level->user_id,
                LevelObject::IS_RATED => $level->rating->stars > 0 ? 10 : 0,
                LevelObject::DIFFICULTY => $level->rating->difficulty->value,
                LevelObject::DOWNLOADS => $level->downloads,
                LevelObject::AUDIO_TRACK => $level->audio_track,
                LevelObject::GAME_VERSION => $level->game_version,
                LevelObject::LIKES => $level->likes,
                LevelObject::LENGTH => $level->length,
                LevelObject::IS_DEMON => $level->rating->demon,
                LevelObject::STARS => $level->rating->stars,
                LevelObject::FEATURED_SCORE => $level->rating->featured_score,
                LevelObject::IS_AUTO => $level->rating->auto,
                LevelObject::PASSWORD => AlgorithmService::encode($level->password, Keys::LEVEL_PASSWORD->value, sha1: false),
                LevelObject::CREATED_AT => $level->created_at
                    ?->locale('en')
                    ->diffForHumans(syntax: true),
                LevelObject::UPDATED_AT => $level->updated_at
                    ?->locale('en')
                    ->diffForHumans(syntax: true),
                LevelObject::ORIGINAL_LEVEL_ID => $level->original_level_id,
                LevelObject::IS_TWO_PLAYER => $level->two_player,
                LevelObject::SONG_ID => $level->song_id,
                LevelObject::COINS => $level->coins,
                LevelObject::IS_COIN_VERIFIED => $level->rating->coin_verified,
                LevelObject::REQUESTED_STARS => $level->requested_stars,
                LevelObject::IS_LDM => $level->ldm,
                LevelObject::SPECIAL_ID => $specialID,
                LevelObject::IS_EPIC => $level->rating->epic,
                LevelObject::DEMON_DIFFICULTY => $level->rating->demon_difficulty->value,
                LevelObject::OBJECTS => min($level->objects, 65535),
            ], ':'),
            AlgorithmService::genLevelDivided($levelString, 40, 39),
            sha1($hash . Salts::LEVEL->value),
            !empty($specialID) ? implode(':', [$level->user->id, $level->user->name, $level->user->uuid]) : config('app.name'),
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function delete(LevelDeleteRequest $request): int
    {
        $data = $request->validated();

        $level = Level::query()
            ->find($data['levelID']);

        if (!$level) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_delete_failed_not_found'), game_response: Response::GAME_LEVEL_DELETE_FAILED_NOT_FOUND->value);
        }

        if ($level->creator->isNot($request->user)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_delete_failed_not_owner'), game_response: Response::GAME_LEVEL_DELETE_FAILED_NOT_OWNER->value);
        }

        (new GameLevelDataStorageService)->delete(['id' => $level->id]);
        $level->delete();

        $this->logGame(__('gdcn.game.action.level_delete_success'));
        return Response::GAME_LEVEL_DELETE_SUCCESS->value;
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function fetchDailyOrWeekly(DailyOrWeeklyLevelFetchRequest $request): string
    {
        $data = $request->validated();

        $now = Carbon::now();
        if (!empty($data['weekly'])) {
            $item = WeeklyLevel::query()
                ->where('apply_at', '<=', $now)
                ->orderBy('apply_at', 'desc')
                ->first();

            if (!$item) {
                throw new GeometryDashChineseServerException(__('gdcn.game.error.level_daily_fetch_failed_not_found'), game_response: Response::GAME_LEVEL_DAILY_FETCH_FAILED_NOT_FOUND->value);
            }

            $leftTime = Carbon::parse('next monday')->diffInSeconds($now);
        } else {
            $item = DailyLevel::query()
                ->where('apply_at', '<=', $now)
                ->orderBy('apply_at', 'desc')
                ->first();

            if (!$item) {
                throw new GeometryDashChineseServerException(__('gdcn.game.error.level_weekly_fetch_failed_not_found'), game_response: Response::GAME_LEVEL_WEEKLY_FETCH_FAILED_NOT_FOUND->value);
            }

            $leftTime = $now->secondsUntilEndOfDay();
        }

        $this->logGame(__(!empty($data['weekly']) ? 'gdcn.game.action.level_weekly_fetch_success' : 'gdcn.game.action.level_daily_fetch_success'));

        return implode('|', [
            $item->id,
            $leftTime,
        ]);
    }

    /**
     * @throws GeometryDashChineseServerException
     */
    public function updateDesc(LevelDescUpdateRequest $request): int
    {
        $data = $request->validated();

        $level = Level::query()
            ->find($data['levelID']);

        if (!$level) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_description_update_failed_not_found'), game_response: Response::GAME_LEVEL_DESCRIPTION_UPDATE_FAILED_NOT_FOUND->value);
        }

        if ($level->creator->isNot($request->user)) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.level_description_update_failed_not_owner'), game_response: Response::GAME_LEVEL_DESCRIPTION_UPDATE_FAILED_NOT_OWNER->value);
        }

        $level->update([
            'desc' => $data['levelDesc']
        ]);

        $this->logGame(__('gdcn.game.action.level_description_update_success'));
        return Response::GAME_LEVEL_DESCRIPTION_UPDATE_SUCCESS->value;
    }
}
