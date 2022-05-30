<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\GeometryDashServer;
use App\Enums\GDCS\LevelSearchType;
use App\Enums\GDCS\LevelTransferType;
use App\Exceptions\GDCS\GeometryDashServerNotFoundException;
use App\Exceptions\GDCS\LevelTransferTargetLinkNotFoundException;
use App\Exceptions\InvalidResponseException;
use App\Exceptions\StorageContentMissingException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\GDCS\LevelTransferInRequest;
use App\Http\Requests\GDCS\LevelTransferOutRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\GDAlgorithm;
use GDCN\GDObject\GDObject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LevelTransferController extends Controller
{
    use HasMessage;

    public function transferIn(LevelTransferInRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            /** @var Account $account */
            $account = $request->user('gdcs');

            try {
                /** @var AccountLink $link */
                $link = $account->links()
                    ->whereKey($data['linkID'])
                    ->firstOrFail();

                $server = GeometryDashServer::from($link->server);
            } catch (ModelNotFoundException) {
                throw new LevelTransferTargetLinkNotFoundException();
            }

            $response = app('proxy')
                ->post($server->value . '/downloadGJLevel22.php', [
                    'levelID' => $data['levelID'],
                    'secret' => 'Wmfd2893gb7'
                ])->body();

            HelperController::checkResponse($response);
            $levelObject = GDObject::split(explode('#', $response)[0], ':');

            if ($levelObject[6] !== (string)$link->target_user_id) {
                throw new LevelTransferTargetLinkNotFoundException();
            }

            $levelInfo = 'transfer:' . $server->name . '|' . $levelObject[1];

            $level = Level::query()
                ->create([
                    'game_version' => $levelObject[13],
                    'user_id' => $account->user->id,
                    'name' => $levelObject[2],
                    'desc' => $levelObject[3],
                    'version' => $levelObject[5],
                    'length' => $levelObject[15],
                    'audio_track' => $levelObject[12],
                    'song_id' => $levelObject[35],
                    'auto' => false,
                    'password' => !is_numeric($levelObject[27]) ? GDAlgorithm::decode($levelObject[27], Keys::LEVEL_PASSWORD->value) : $levelObject[27],
                    'original_level_id' => $levelObject[30],
                    'two_player' => $levelObject[31],
                    'objects' => $levelObject[45],
                    'coins' => $levelObject[37],
                    'requested_stars' => $levelObject[39],
                    'unlisted' => false,
                    'ldm' => $levelObject[40],
                    'extra_string' => $levelObject[36],
                    'level_info' => $levelInfo
                ]);

            app('storage:gdcs.level_data')
                ->put($level->id, $levelObject[4]);

            $account->levelTransferRecords()
                ->create([
                    'type' => LevelTransferType::OFFICIAL,
                    'original_level_id' => $levelObject[1],
                    'level_id' => $level->id
                ]);

            $this->message(__('messages.level_transfer.success'), ['type' => 'success']);
        } catch (GeometryDashServerNotFoundException) {
            $this->message(__('messages.level_transfer.level_not_found'), ['type' => 'error']);
        } catch (InvalidResponseException) {
            $this->message(__('messages.robtop_now_not_like_you'), ['type' => 'error']);
        } catch (LevelTransferTargetLinkNotFoundException) {
            $this->message(__('messages.level_transfer.creator_link_not_found'), ['type' => 'error']);
            return to_route('gdcs.tools.account.link.list');
        }

        return back();
    }

    public function loadRemoteLevels(int $userID): array|RedirectResponse
    {
        $response = app('proxy')
            ->post(config('gdproxy.base_url') . '/getGJLevels21.php', [
                'type' => LevelSearchType::USER->value,
                'str' => $userID,
                'secret' => 'Wmfd2893gb7'
            ])->body();

        try {
            HelperController::checkResponse($response);
        } catch (InvalidResponseException) {
            $this->message(__('messages.robtop_now_not_like_you'), ['type' => 'error']);
            return back();
        }

        $levelData = Arr::get(explode('#', $response), 0);
        if (empty($levelData)) {
            $this->message(__('messages.level_transfer.level_not_found'), ['type' => 'error']);
            return back();
        }

        return collect(
            explode('|', $levelData)
        )->map(function (string $level) {
            $levelObject = GDObject::split($level, ':');

            return [
                'label' => $levelObject[2] . ' [' . $levelObject[1] . ']',
                'value' => $levelObject[1]
            ];
        })->toArray();
    }

    public function transferOut(LevelTransferOutRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            /** @var Account $account */
            $account = $request->user('gdcs');

            /** @var Level $level */
            $level = $account->user
                ->levels()
                ->whereKey($data['levelID'])
                ->firstOrFail();

            /** @var AccountLink $link */
            $link = $account->links()
                ->whereKey($data['linkID'])
                ->firstOrFail();

            $storage = app(LevelDataController::class);
            $levelString = $storage->get($level->id);

            $response = app('proxy')
                ->post($link->server . '/uploadGJLevel21.php', [
                    'gameVersion' => $level->game_version,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $link->target_account_id,
                    'gjp' => GDAlgorithm::encode($data['password'], Keys::ACCOUNT_PASSWORD->value, sha1: false),
                    'userName' => $link->target_name,
                    'levelID' => 0,
                    'levelName' => $level->name,
                    'levelDesc' => $level->desc,
                    'levelVersion' => $level->version,
                    'levelLength' => $level->length,
                    'audioTrack' => $level->audio_track,
                    'auto' => false,
                    'password' => $level->password,
                    'original' => $level->original_level_id,
                    'twoPlayer' => $level->two_player,
                    'songID' => $level->song_id,
                    'objects' => $level->objects,
                    'coins' => $level->coins,
                    'requestedStars' => $level->requested_stars,
                    'unlisted' => $level->unlisted,
                    'ldm' => $level->ldm,
                    'levelString' => $levelString,
                    'wt' => 0,
                    'wt2' => 0,
                    'seed' => Str::random(),
                    'seed2' => GDAlgorithm::encode(GDAlgorithm::genLevelDivided($levelString, 50, 49), Keys::LEVEL_SEED->value, sha1: false),
                    'extraString' => $level->extra_string,
                    'levelInfo' => $level->level_info,
                    'secret' => 'Wmfd2893gb7'
                ])->body();

            HelperController::checkResponse($response);
            $this->message(__('messages.level_transfer.success_with_id', ['id' => $response]), ['type' => 'success']);
        } catch (StorageContentMissingException|ModelNotFoundException) {
            $this->message(__('messages.level_transfer.level_not_found'), ['type' => 'error']);
        } catch (InvalidResponseException) {
            $this->message(__('messages.robtop_now_not_like_you'), ['type' => 'error']);
        }

        return back();
    }
}
