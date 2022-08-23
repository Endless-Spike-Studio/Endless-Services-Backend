<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountDataLoadRequest;
use App\Http\Requests\GDCS\AccountDataSaveRequest;
use App\Http\Requests\GDCS\AccountDataServerAddressGetRequest;
use App\Http\Traits\GameLog;

class AccountDataController extends Controller
{
    use GameLog;

    public function getDataServerAddress(AccountDataServerAddressGetRequest $request): string
    {
        $this->logGame(__('gdcn.game.action.account_data_server_url_fetch_success'));
        return $request->getHost();
    }

    public function save(AccountDataSaveRequest $request): int
    {
        $data = $request->validated();
        $request->account->data = $data['saveData'];
        $this->logGame(__('gdcn.game.action.account_data_save_success'));
        return Response::GAME_ACCOUNT_DATA_SAVE_SUCCESS->value;
    }

    /**
     * @throws GameException
     */
    public function load(AccountDataLoadRequest $request): int|string
    {
        $data = $request->validated();
        $content = $request->account->data;

        if (empty($content)) {
            throw new GameException(__('gdcn.game.error.account_data_load_failed_not_found'), response_code: Response::GAME_ACCOUNT_DATA_LOAD_FAILED_NOT_FOUND->value);
        }

        $this->logGame(__('gdcn.game.action.account_data_load_success'));
        return implode(';', [
            $content,
            $data['gameVersion'],
            $data['binaryVersion'],
            $content
        ]);
    }
}
