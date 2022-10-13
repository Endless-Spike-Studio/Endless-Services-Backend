<?php

namespace App\Http\Controllers\GDCS\Web\Tools;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\Tools\LevelTransferInRequest;
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
use Inertia\Inertia;
use Psr\Http\Client\ClientExceptionInterface;

class LevelTransferController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function loadLevels(AccountLink $link)
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
    public function fromRemote(LevelTransferInRequest $request)
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
}
