<?php

namespace App\Http\Presenters\GDCS;

use App\Http\Controllers\GDCS\LevelTransferController;
use App\Models\GDCS\AccountLink;
use App\Models\GDCS\Level;
use App\Repositories\GDCS\AccountLinkRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelTransferToolPresenter
{
    public function in(): Response
    {
        return Inertia::render('GDCS/Tools/Level/Transfer/In', [
            'links' => $this->links(),
            'levels' => Inertia::lazy(static function () {
                return app(LevelTransferController::class)
                    ->loadRemoteLevels(
                        app(AccountLinkRepository::class)
                            ->findByAccount(
                                Auth::guard('gdcs')
                                    ->id(),
                                Request::get('link', 0)
                            )->value('target_user_id')
                    );
            })
        ]);
    }

    protected function links(): array
    {
        return Request::user('gdcs')
            ->getRelationValue('links')
            ->map(fn(AccountLink $link) => [
                'label' => $link->target_name . ' [' . $link->target_account_id . ', ' . $link->target_user_id . ']',
                'value' => $link->id
            ])->toArray();
    }

    public function out(): Response
    {
        return Inertia::render('GDCS/Tools/Level/Transfer/Out', [
            'levels' => $this->levels(),
            'links' => $this->links()
        ]);
    }

    protected function levels(): array
    {
        return Request::user('gdcs')
            ->getRelationValue('user')
            ->getRelationValue('levels')
            ->map(fn(Level $level) => [
                'label' => "$level->name [$level->id]",
                'value' => $level->id
            ])->toArray();
    }
}
