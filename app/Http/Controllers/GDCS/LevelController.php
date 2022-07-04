<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\LevelSearchType;
use App\Enums\GDCS\SpecialLevelID;
use App\Enums\Response;
use App\Exceptions\GDCS\LevelSearchAuthFailedException;
use App\Exceptions\GDCS\LevelSearchNotSupportedTypeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\DailyOrWeeklyLevelFetchRequest;
use App\Http\Requests\GDCS\LevelDeleteRequest;
use App\Http\Requests\GDCS\LevelDescUpdateRequest;
use App\Http\Requests\GDCS\LevelDownloadRequest;
use App\Http\Requests\GDCS\LevelSearchRequest;
use App\Http\Requests\GDCS\LevelUploadRequest;
use App\Models\GDCS\DailyLevel;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelDownloadRecord;
use App\Models\GDCS\LevelGauntlet;
use App\Models\GDCS\WeeklyLevel;
use Carbon\Carbon;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\enums\Salts;
use GDCN\GDAlgorithm\GDAlgorithm;
use GDCN\GDObject\GDObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LevelController extends Controller
{
    public function upload(LevelUploadRequest $request)
    {
        $data = $request->validated();

        $level = Level::query()
            ->updateOrCreate([
                'id' => $data['levelID'] ?? 0,
                'user_id' => $request->user->id
            ], [
                'game_version' => $data['gameVersion'],
                'user_id' => $request->user->id,
                'name' => $data['levelName'],
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
                'level_info' => $data['levelInfo']
            ]);

        app('storage:gdcs.level_data')->put($level->id, $data['levelString']);
        return $level->id;
    }

    public function search(LevelSearchRequest $request): int|string
    {
        try {
            $data = $request->validated();
            $query = Level::query();

            if (!empty($data['gauntlet'])) {
                $this->processSearchGauntlet($request, $query);
            } else {
                $this->processSearchType($request, $query);
                $this->processSearchAdvancedOptions($request, $query);
            }

            $data['page'] ??= 0;
            $perPage = config('gdcs.perPage', 10);

            $users = [];
            $songs = [];
            $hash = null;

            return implode('#', [
                $query->forPage(++$data['page'], $perPage)
                    ->get()
                    ->map(function (Level $level) use ($data, &$users, &$songs, &$hash) {
                        $user = $level->user;
                        $users[] = implode(':', [
                            $user->id,
                            $user->name,
                            $user->uuid
                        ]);

                        if (!empty($level->song)) {
                            $songs[$level->song_id] = $level->song->object;
                        }

                        $hash .= implode(null, [
                            substr($level->id, 0, 1),
                            substr($level->id, -1, 1),
                            $level->rating->stars,
                            (int)$level->rating->coin_verified
                        ]);

                        return GDObject::merge([
                            1 => $level->id,
                            2 => $level->name,
                            3 => $level->desc,
                            5 => $level->version,
                            6 => $level->user_id,
                            8 => $level->rating->stars > 0 ? 10 : 0,
                            9 => $level->rating->difficulty->value,
                            10 => $level->downloads,
                            12 => $level->audio_track,
                            13 => $level->game_version,
                            14 => $level->likes,
                            15 => $level->length,
                            17 => $level->rating->demon,
                            18 => $level->rating->stars,
                            19 => $level->rating->featured_score,
                            25 => $level->rating->auto,
                            30 => $level->original_level_id,
                            31 => $level->two_player,
                            35 => $level->song_id,
                            37 => $level->coins,
                            38 => $level->rating->coin_verified,
                            39 => $level->requested_stars,
                            42 => $level->rating->epic,
                            43 => $level->rating->demon_difficulty->value,
                            44 => $data['gauntlet'] ?? 0,
                            45 => max($level->objects, 65535)
                        ], ':');
                    })->join('|'),
                implode('|', $users),
                implode('~:~', $songs),
                GDAlgorithm::genPage($data['page'], $query->count(), $perPage),
                sha1($hash . Salts::LEVEL->value)
            ]);
        } catch (LevelSearchNotSupportedTypeException) {
            return Response::LEVEL_SEARCH_FAILED_NOT_SUPPORT_TYPE->value;
        } catch (LevelSearchAuthFailedException) {
            return Response::LEVEL_SEARCH_FAILED_AUTH_FAILED->value;
        }
    }

    protected function processSearchGauntlet(LevelSearchRequest $request, Builder|Level $query): void
    {
        $data = $request->validated();
        $gauntlet = LevelGauntlet::query()
            ->findOrFail($data['gauntlet']);

        $query->whereKey($gauntlet->levels);
    }

    /**
     * @throws LevelSearchNotSupportedTypeException
     * @throws LevelSearchAuthFailedException
     */
    protected function processSearchType(LevelSearchRequest $request, Builder|Level $query): void
    {
        $data = $request->validated();

        switch ($data['type']) {
            case LevelSearchType::SEARCH->value:
                $query->whereKey($data['str']);
                $query->orWhere('name', 'LIKE', $data['str'] . '%');
                $query->orderByDesc('likes');
                break;
            case LevelSearchType::MOST_DOWNLOADED->value:
                $query->orderByDesc('downloads');
                break;
            case LevelSearchType::MOST_LIKED->value:
                $query->orderByDesc('likes');
                break;
            case LevelSearchType::TRENDING->value:
                $query->where('created_at', '>=', now()->subDays(7));
                $query->orderByDesc('likes');
                break;
            case LevelSearchType::RECENT->value:
                $query->orderByDesc('created_at');
                break;
            case LevelSearchType::USER->value:
                $query->where('user_id', $data['str']);
                break;
            case LevelSearchType::FEATURED->value:
            case LevelSearchType::ALSO_FEATURED->value:
                $query->whereHas('rating', function ($query) {
                    $query->where('featured_score', '>', 0);
                });
                break;
            case LevelSearchType::MAGIC->value:
                $query->where('objects', '>', 9999);
                break;
            case LevelSearchType::LIST->value:
                $query->whereKey($data['str']);
                break;
            case LevelSearchType::AWARDED->value:
                $query->whereHas('rating', function ($query) {
                    $query->orderByDesc('created_at');
                });
                break;
            case LevelSearchType::FOLLOWED->value:
                $query->whereIn('user_id', $data['followed']);
                break;
            case LevelSearchType::FRIENDS->value:
                if (!$request->auth()) {
                    throw new LevelSearchAuthFailedException();
                }

                $query->whereIn('user_id', $request->account->friend_user_ids_with_self);
                break;
            case LevelSearchType::HALL_OF_FAME->value:
                $query->whereHas('rating', function ($query) {
                    $query->where('epic', true);
                });
                break;
            case LevelSearchType::MOD_SENT->value:
            case LevelSearchType::WORLD_MOST_LIKED->value:
            case LevelSearchType::UNKNOWN->value:
            default:
                throw new LevelSearchNotSupportedTypeException();
        }
    }

    protected function processSearchAdvancedOptions(LevelSearchRequest $request, Builder|Level $query): void
    {
        $data = $request->validated();
        $type = (int)$data['type'];

        if (
            $type !== LevelSearchType::USER->value ||
            (!is_numeric($data['str']) && $type === LevelSearchType::SEARCH->value)
        ) {
            $query->whereNot('unlisted', true);
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

        if (!empty($data['epic'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('epic', true);
            });
        }

        if (!empty($data['song'])) {
            $query->where(!empty($data['customSong']) ? 'song_id' : 'audio_track', $data['song']);
        }

        if (!empty($data['noStar'])) {
            $query->whereHas('rating', function ($query) {
                $query->where('stars', 0);
            });
        }

        if (!empty($data['coins'])) {
            $query->where('coins', '>', 0);
        }

        if (!empty($data['twoPlayer'])) {
            $query->where('two_player', true);
        }
    }

    public function download(LevelDownloadRequest $request): int|string
    {
        try {
            $data = $request->validated();

            $now = Carbon::now();
            switch ($data['levelID']) {
                case SpecialLevelID::DAILY->value:
                    $item = DailyLevel::query()
                        ->where('apply_at', '<=', $now)
                        ->orderBy('apply_at', 'desc')
                        ->firstOrFail();

                    $level = $item->level;
                    $specialID = $item->id;
                    break;
                case SpecialLevelID::WEEKLY->value:
                    $item = WeeklyLevel::query()
                        ->where('apply_at', '<=', $now)
                        ->orderBy('apply_at', 'desc')
                        ->firstOrFail();

                    $level = $item->level;
                    $specialID = $item->id;
                    break;
                default:
                    $level = Level::query()
                        ->findOrFail($data['levelID']);

                    $specialID = 0;
                    break;
            }

            $levelString = app('storage:gdcs.level_data')->get($level->id);

            $hash = implode(',', [
                $level->user_id,
                $level->rating->stars,
                (int)$level->rating->demon,
                $level->id,
                (int)$level->rating->coin_verified,
                $level->rating->featured_score,
                $level->password,
                $specialID
            ]);

            $request->auth();
            $ip = $request->ip();
            $userID = $request->user->id ?? 0;

            $record = LevelDownloadRecord::query()
                ->firstOrCreate([
                    'level_id' => $level->id,
                    'user_id' => $userID,
                    'ip' => $ip
                ]);

            if ($record->wasRecentlyCreated) {
                $level->downloads++;
                $level->save();
            }

            return implode('#', [
                GDObject::merge([
                    1 => $level->id,
                    2 => $level->name,
                    3 => $level->desc,
                    4 => $levelString,
                    5 => $level->version,
                    6 => $level->user_id,
                    8 => $level->rating->stars > 0 ? 10 : 0,
                    9 => $level->rating->difficulty->value,
                    10 => $level->downloads,
                    12 => $level->audio_track,
                    13 => $level->game_version,
                    14 => $level->likes,
                    15 => $level->length,
                    17 => $level->rating->demon,
                    18 => $level->rating->stars,
                    19 => $level->rating->featured_score,
                    25 => $level->rating->auto,
                    27 => GDAlgorithm::encode($level->password, Keys::LEVEL_PASSWORD->value, sha1: false),
                    28 => $level->created_at?->locale('en')->diffForHumans(syntax: true),
                    29 => $level->updated_at?->locale('en')->diffForHumans(syntax: true),
                    30 => $level->original_level_id,
                    31 => $level->two_player,
                    35 => $level->song_id,
                    37 => $level->coins,
                    38 => $level->rating->coin_verified,
                    39 => $level->requested_stars,
                    40 => $level->ldm,
                    41 => $specialID,
                    42 => $level->rating->epic,
                    43 => $level->rating->demon_difficulty->value,
                    45 => min($level->objects, 65535)
                ], ':'),
                GDAlgorithm::genLevelDivided($levelString, 40, 39),
                sha1($hash . Salts::LEVEL->value),
                !empty($specialID) ? implode(':', [$level->user->id, $level->user->name, $level->user->uuid]) : 'GDCS'
            ]);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return Response::LEVEL_DOWNLOAD_FAILED_DATA_MISSING->value;
        } catch (ModelNotFoundException) {
            return Response::LEVEL_DOWNLOAD_FAILED_NO_LEVEL->value;
        }
    }

    public function delete(LevelDeleteRequest $request): int
    {
        $data = $request->validated();
        $level = Level::query()
            ->findOrFail($data['levelID']);

        if ($level->user_id === $request->user->id) {
            foreach (config('gdcs.storages.levelData') as $storage) {
                Storage::disk($storage['disk'])->delete(str_replace('@', $level->id, $storage['format']));
            }

            $level->delete();
            return Response::LEVEL_DELETE_SUCCESS->value;
        }

        return Response::LEVEL_DELETE_FAILED_NO_PERMISSION->value;
    }

    public function fetchDailyOrWeekly(DailyOrWeeklyLevelFetchRequest $request): string
    {
        $data = $request->validated();

        $now = Carbon::now();
        if (!empty($data['weekly'])) {
            $item = WeeklyLevel::query()
                ->where('apply_at', '<=', $now)
                ->orderBy('apply_at', 'desc')
                ->first();

            $leftTime = Carbon::parse('next monday')->diffInSeconds($now);
        } else {
            $item = DailyLevel::query()
                ->where('apply_at', '<=', $now)
                ->orderBy('apply_at', 'desc')
                ->first();

            $leftTime = $now->secondsUntilEndOfDay();
        }

        return implode('|', [
            $item->id ?? Response::DAILY_OR_WEEKLY_LEVEL_FETCH_FAILED_NOT_FOUND->value,
            $leftTime
        ]);
    }

    public function updateDesc(LevelDescUpdateRequest $request): int
    {
        $data = $request->validated();
        $level = Level::query()
            ->findOrFail($data['levelID']);

        if ($level->user_id === $request->user->id) {
            $level->desc = $data['levelDesc'];
            return Response::LEVEL_DESC_UPDATE_SUCCESS->value;
        }

        return Response::LEVEL_DESC_UPDATE_FAILED_NO_PERMISSION->value;
    }
}
