<?php

namespace App\Services\Storage;

class SongStorageService extends BaseStorageService
{
    public function __construct()
    {
        parent::__construct(
            config('gdcn.storages.songs')
        );
    }
}
