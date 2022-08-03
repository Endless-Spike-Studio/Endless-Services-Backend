<?php

namespace App\Services\Storage;

use App\Services\BaseStorageService;

class SongStorageService extends BaseStorageService
{
    public function __construct()
    {
        parent::__construct(
            config('gdcn.storages.songs')
        );
    }
}
