<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountBlockRequest;
use App\Http\Requests\GDCS\AccountUnblockRequest;
use App\Services\GDCS\Game\AccountBlockService;

class AccountBlockController extends Controller
{
    public function __construct(
        protected AccountBlockService $service
    )
    {
    }

    /**
     * @throws GameException
     */
    public function block(AccountBlockRequest $request): int
    {
        $data = $request->validated();
        $this->service->store($data['accountID'], $data['targetAccountID']);
        return Response::GAME_ACCOUNT_BLOCK_SUCCESS->value;
    }

    /**
     * @throws GameException
     */
    public function unblock(AccountUnblockRequest $request): int
    {
        $data = $request->validated();
        $this->service->destroy($data['accountID'], $data['targetAccountID']);
        return Response::GAME_ACCOUNT_UNBLOCK_SUCCESS->value;
    }
}
