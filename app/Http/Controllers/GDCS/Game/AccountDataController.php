<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountDataLoadRequest;
use App\Http\Requests\GDCS\AccountDataSaveRequest;
use App\Http\Requests\GDCS\AccountDataServerAddressGetRequest;

class AccountDataController extends Controller
{
    public function getDataServerAddress(AccountDataServerAddressGetRequest $request): string
    {
        return $request->getHost();
    }

    public function save(AccountDataSaveRequest $request): int
    {
        $data = $request->validated();
        $request->account->data = $data['saveData'];
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
            throw new GameException(__('error.game.account.save.not_found'), log_context: [
                'account_id' => $request->account->id
            ], response_code: Response::GAME_ACCOUNT_DATA_LOAD_FAILED_NOT_FOUND->value);
        }

        return implode(';', [
            $content,
            $data['gameVersion'],
            $data['binaryVersion'],
            $content
        ]);
    }
}
