<?php

namespace App\EndlessProxy\Channels;

use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Request;

class NetworkChannel extends Channel
{
    public function __construct()
    {
        parent::__construct(
            sha1(
                implode('|', [
                    __CLASS__, Request::ip()
                ])
            )
        );
    }
}