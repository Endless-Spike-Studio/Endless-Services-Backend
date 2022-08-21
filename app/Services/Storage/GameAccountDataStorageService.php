<?php

namespace App\Services\Storage;

class GameAccountDataStorageService extends BaseStorageService
{
    public function __construct()
    {
        parent::__construct(
            config('gdcn.storages.game.accounts')
        );
    }
}
