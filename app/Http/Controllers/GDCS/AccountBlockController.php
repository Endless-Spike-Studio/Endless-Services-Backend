<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountBlockRequest;
use App\Http\Requests\GDCS\AccountUnblockRequest;
use App\Services\GDCS\AccountBlockService;

class AccountBlockController extends Controller
{
    public function __construct(
        protected AccountBlockService $service
    )
    {
    }

    public function block(AccountBlockRequest $request): int
    {
        $data = $request->validated();

        return $this->service->create($data['accountID'], $data['targetAccountID'])
            ? Response::ACCOUNT_BLOCK_SUCCESS->value
            : Response::ACCOUNT_BLOCK_FAILED->value;
    }

    public function unblock(AccountUnblockRequest $request): int
    {
        $data = $request->validated();

        return $this->service->delete($data['accountID'], $data['targetAccountID'])
            ? Response::ACCOUNT_UNBLOCK_SUCCESS->value
            : Response::ACCOUNT_UNBLOCK_FAILED->value;
    }
}
