<?php

namespace App\Http\Controllers\GDCS\Web\Tools;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\Tools\LevelTransferInRequest;
use App\Http\Requests\GDCS\Web\Tools\LevelTransferOutRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use App\Services\Game\AlgorithmService;
use App\Services\Game\ObjectService;
use App\Services\Game\ResponseService;
use App\Services\ProxyService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Psr\Http\Client\ClientExceptionInterface;

class LevelTransferController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function loadRemoteLevelsForTransferIn(AccountLink $link)
    {
        try {
            /** @var Account $account */
            $account = Auth::guard('gdcs')->user();

            if ($link->account->isNot($account)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_level_load_failed_not_link_owner'));
            }

            $request = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($link->server . '/getGJLevels21.php', [
                    'type' => 5,
                    'str' => $link->target_user_id,
                    'secret' => 'Wmfd2893gb7'
                ]);

            if (!$request->ok()) {
                throw new WebException(__('gdcn.tools.error.level_transfer_level_load_failed_request_error'));
            }

            $response = $request->body();
            ResponseService::check($response);

            $levels = collect(
                explode('|', Arr::get(explode('#', $response), 0))
            )->map(function ($level) use ($account, $link) {
                $level = ObjectService::split($level, ':');

                if (!Arr::has($level, [1, 2, 3])) {
                    return null;
                }

                return [
                    'id' => $level[1],
                    'name' => $level[2],
                    'desc' => $level[3],
                    'transferred' => $account->levelTransferRecords()
                        ->where('server', $link->server)
                        ->where('original_level_id', $level[1])
                        ->exists()
                ];
            })->filter(function ($item) {
                return !empty($item);
            })->toArray();

            return Inertia::render('GDCS/Tools/Level/Transfer/In/LevelSelector', [
                'linkID' => $link->id,
                'levels' => $levels
            ]);
        } catch (ClientExceptionInterface) {
            throw new WebException(__('gdcn.tools.error.level_transfer_level_load_failed_request_exception'));
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_level_load_failed_response_error'));
        }
    }

    /**
     * @throws WebException
     */
    public function transferInFromRemote(LevelTransferInRequest $request)
    {
        try {
            $data = $request->validated();

            $link = AccountLink::query()
                ->find($data['linkID']);

            if (empty($link)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_link_not_found'));
            }

            /** @var Account $account */
            $account = Auth::guard('gdcs')->user();

            if ($link->account->isNot($account)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_not_link_owner'));
            }

            $request = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($link->server . '/downloadGJLevel22.php', [
                    'levelID' => $data['levelID'],
                    'secret' => 'Wmfd2893gb7'
                ]);

            if (!$request->ok()) {
                throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_request_error'));
            }

            $response = $request->body();
            ResponseService::check($response);

            $recordQuery = $account->levelTransferRecords()
                ->where('server', $link->server)
                ->where('original_level_id', $data['levelID']);

            if ($recordQuery->exists()) {
                throw new WebException(
                    __('gdcn.tools.error.level_transfer_in_failed_already_transferred', [
                        'id' => $recordQuery->value('level_id')
                    ])
                );
            }

            $levelObjectString = Arr::get(explode('#', $response), 0);
            $levelObject = ObjectService::split($levelObjectString, ':');

            if (!Arr::has($levelObject, [27, 2, 13, 3, 5, 15, 12, 35, 25, 1, 31, 45, 37, 39, 40, 36, 4])) {
                throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_invalid_object'));
            }

            $password = $levelObject[27];
            $levelInfo = sha1('level_transfer_in:' . $link->server . '|' . $data['levelID']);

            $levelQuery = Level::query()
                ->where('level_info', $levelInfo);

            if ($levelQuery->exists()) {
                throw new WebException(
                    __('gdcn.tools.error.level_transfer_in_failed_already_transferred', [
                        'id' => $levelQuery->value('id')
                    ])
                );
            }

            if (!is_numeric($password)) {
                $password = AlgorithmService::decode($levelObject[27], Keys::LEVEL_PASSWORD->value);
            }

            $level = Level::query()
                ->create([
                    'name' => $levelObject[2],
                    'user_id' => $link->account->user->id,
                    'game_version' => $levelObject[13],
                    'desc' => $levelObject[3],
                    'version' => $levelObject[5],
                    'length' => $levelObject[15],
                    'audio_track' => $levelObject[12],
                    'song_id' => $levelObject[35],
                    'auto' => (bool)$levelObject[25],
                    'password' => $password,
                    'original_level_id' => $levelObject[1],
                    'two_player' => $levelObject[31],
                    'objects' => $levelObject[45],
                    'coins' => $levelObject[37],
                    'requested_stars' => $levelObject[39],
                    'unlisted' => false,
                    'ldm' => $levelObject[40],
                    'extra_string' => $levelObject[36],
                    'level_info' => $levelInfo
                ]);

            $level->data = $levelObject[4];
            $account->levelTransferRecords()
                ->create([
                    'server' => $link->server,
                    'original_level_id' => $levelObject[1],
                    'level_id' => $level->id
                ]);

            $this->pushSuccessMessage(
                __('gdcn.tools.action.level_transfer_in_success', [
                    'id' => $level->id
                ])
            );
        } catch (ClientExceptionInterface) {
            throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_request_exception'));
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_in_failed_response_error'));
        }

        return back();
    }

    /**
     * @throws WebException
     */
    public function loadLinksForTransferOut(Level $level)
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        if ($level->creator()->isNot($account->user)) {
            throw new WebException(__('gdcn.tools.error.level_transfer_link_load_failed_not_level_owner'));
        }

        return Inertia::render('GDCS/Tools/Level/Transfer/Out/AccountSelector', [
            'levelID' => $level->id,
            'links' => $account->links
        ]);
    }

    /**
     * @throws WebException
     */
    public function transferOutToRemote(LevelTransferOutRequest $request)
    {
        try {
            $data = $request->validated();
            $link = AccountLink::query()
                ->find($data['linkID']);

            if (empty($link)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_out_failed_link_not_found'));
            }

            $level = Level::query()
                ->find($data['levelID']);

            if (empty($level)) {
                throw new WebException(__('gdcn.tools.error.level_transfer_out_failed_level_not_found'));
            }

            $request = ProxyService::instance()
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
                    'seed2' => AlgorithmService::encode(
                        AlgorithmService::genLevelDivided($level->data, 50, 49),
                        Keys::LEVEL_SEED->value,
                        sha1: false
                    ),
                    'extraString' => $level->extra_string,
                    'levelInfo' => $level->level_info,
                    'secret' => 'Wmfd2893gb7',
                ]);

            if (!$request->ok()) {
                throw new WebException(__('gdcn.tools.error.level_transfer_out_failed_request_error'));
            }

            $response = $request->body();
            ResponseService::check($response);

            $this->pushSuccessMessage(
                __('gdcn.tools.action.level_transfer_out_success')
            );

            return back();
        } catch (ClientExceptionInterface) {
            throw new WebException(__('gdcn.tools.error.level_transfer_out_failed_request_exception'));
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_out_failed_response_error'));
        }
    }
}
