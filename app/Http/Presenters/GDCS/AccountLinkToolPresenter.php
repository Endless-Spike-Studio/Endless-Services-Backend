<?php

namespace App\Http\Presenters\GDCS;

use App\Enums\GDCS\GeometryDashServer;
use App\Models\GDCS\AccountLink;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountLinkToolPresenter
{
    public function create(): Response
    {
        return Inertia::render('GDCS/Tools/Account/Link/Create', [
            'servers' => collect(
                GeometryDashServer::cases()
            )->map(fn(GeometryDashServer $server) => [
                'label' => __('servers.' . $server->name),
                'value' => $server->value
            ])->toArray()
        ]);
    }

    public function list(): Response
    {
        return Inertia::render('GDCS/Tools/Account/Link/List', [
            'links' => Request::user('gdcs')
                ->getRelationValue('links')
                ->map(fn(AccountLink $link) => [
                    ...$link->toArray(),
                    'server_name' => __('servers.' . GeometryDashServer::from($link->server)->name)
                ])->toArray()
        ]);
    }
}
