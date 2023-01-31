<?php

namespace App\Http\Presenters\GDCS;

use App\Exceptions\WebException;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use App\Services\Game\LevelTransferService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelTransferToolPresenter
{
    public function renderHome(): Response
    {
        $account = Auth::guard('gdcs')->user();

        return Inertia::render('GDCS/Tools/Level/Transfer/Home', [
            'links' => Inertia::lazy(function () use ($account) {
                return $account->links()
                    ->select(['id', 'account_id', 'server', 'target_name', 'target_account_id', 'target_user_id', 'created_at'])
                    ->paginate();
            }),
            'levels' => Inertia::lazy(function () use ($account) {
                return $account->user->levels()
                    ->select(['id', 'name', 'desc', 'user_id'])
                    ->paginate();
            })
        ]);
    }

    /**
     * @throws WebException
     */
    public function renderIn(AccountLink $link, LevelTransferService $service): Response
    {
        $account = Auth::guard('gdcs')->user();

        if ($link->account->isNot($account)) {
            throw new WebException(__('gdcn.tools.error.level_transfer_failed_not_link_owner'));
        }

        return Inertia::render('GDCS/Tools/Level/Transfer/In/LevelSelect', [
            'link' => $link->only(['id', 'target_name']),
            'levels' => $service->loadLevelsFromRemote($link->server, $link->target_user_id, Request::get('page', 0))
        ]);
    }

    /**
     * @throws WebException
     */
    public function renderOut(Level $level): Response
    {
        $account = Auth::guard('gdcs')->user();

        if ($level->creator->isNot($account->user)) {
            throw new WebException(__('gdcn.tools.error.level_transfer_failed_not_level_owner'));
        }

        return Inertia::render('GDCS/Tools/Level/Transfer/Out/LinkSelect', [
            'level' => $level->only(['id', 'name']),
            'links' => $account->links()
                ->select(['id', 'account_id', 'server', 'target_name', 'target_account_id', 'target_user_id', 'created_at'])
                ->paginate()
        ]);
    }
}
