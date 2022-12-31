<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\LevelTransferType;
use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LevelTransferToolInRequest;
use App\Http\Requests\GDCS\Web\LevelTransferToolOutRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use App\Services\Game\AlgorithmService;
use App\Services\Game\ObjectService;
use App\Services\Game\ResponseService;
use App\Services\ProxyService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LevelTransferToolController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function in(AccountLink $link, LevelTransferToolInRequest $request)
    {
        try {
            $data = $request->validated();
            $account = Auth::guard('gdcs')->user();

            if ($link->account->isNot($account)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_failed_not_link_owner'));
            }

            $response = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($link->server . '/downloadGJLevel22.php', [
                    'levelID' => $data['levelID'],
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            ResponseService::check($response);
            $levelObject = ObjectService::split(Arr::get(explode('#', $response), 0), ':');

            $level = Level::query()
                ->firstOrCreate([
                    'level_info' => json_encode([
                        'type' => 'transfer',
                        'server' => $link->server,
                        'id' => $levelObject[1]
                    ])
                ], [
                    'game_version' => $levelObject[13],
                    'user_id' => $account->user->id,
                    'name' => $levelObject[2],
                    'desc' => $levelObject[3],
                    'version' => $levelObject[5],
                    'length' => $levelObject[15],
                    'audio_track' => $levelObject[12],
                    'song_id' => $levelObject[35],
                    'auto' => false,
                    'password' => !is_numeric($levelObject[27]) ? AlgorithmService::decode($levelObject[27], Keys::LEVEL_PASSWORD->value) : $levelObject[27],
                    'original_level_id' => $levelObject[30],
                    'two_player' => $levelObject[31],
                    'objects' => $levelObject[45],
                    'coins' => $levelObject[37],
                    'requested_stars' => $levelObject[39],
                    'unlisted' => false,
                    'ldm' => $levelObject[40],
                    'extra_string' => $levelObject[36]
                ]);

            $record = $account->levelTransferRecords()
                ->firstOrCreate([
                    'server' => $link->server,
                    'original_level_id' => $levelObject[1]
                ], [
                    'level_id' => $level->id
                ]);

            $level->data = $levelObject[4];
            if (!$level->wasRecentlyCreated || !$record->wasRecentlyCreated) {
                throw new WebException(__('gdcn.tools.error.level_transfer_failed_level_already_transferred_with_id', ['id' => $level->id]));
            }

            $this->pushSuccessMessage(__('gdcn.tools.action.level_transfer_success_with_id', ['id' => $level->id]));
            return back();
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_failed_level_download_failed'));
        }
    }

    /**
     * @throws WebException
     */
    public function out(Level $level, LevelTransferToolOutRequest $request)
    {
        try {
            $data = $request->validated();
            $account = Auth::guard('gdcs')->user();
            $link = AccountLink::findOrFail($data['linkID']);

            if ($link->account->isNot($account)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_failed_not_link_owner'));
            }

            if ($level->creator->isNot($account->user)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_failed_not_level_owner'));
            }

            $response = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($link->server . '/uploadGJLevel21.php', [
                    'gameVersion' => $level->game_version,
                    'binaryVersion' => 35,
                    'gdw' => false,
                    'accountID' => $link->target_account_id,
                    'gjp' => AlgorithmService::encode($data['password'], Keys::ACCOUNT_PASSWORD->value, sha1: false),
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
                    'levelString' => $level->data,
                    'wt' => 0,
                    'wt2' => 0,
                    'seed' => Str::random(),
                    'seed2' => AlgorithmService::encode(AlgorithmService::genLevelDivided($level->data, 50, 49), Keys::LEVEL_SEED->value, sha1: false),
                    'extraString' => $level->extra_string,
                    'levelInfo' => $level->level_info,
                    'secret' => 'Wmfd2893gb7'
                ])->body();

            ResponseService::check($response);
            $this->pushSuccessMessage(__('gdcn.tools.action.level_transfer_success_with_id', ['id' => $response]));

            return back();
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_failed_level_upload_failed'));
        }
    }
}
