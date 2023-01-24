<?php

namespace App\Exceptions;

use App\Models\NGProxy\Song;

class NewGroundsProxyException extends BaseException
{
    public Song $song;
    protected string $logChannel = 'gdcn';

    public function setSong(Song $song): static
    {
        $this->song = $song;
        return $this;
    }

    protected function formatMessage(string $message): string
    {
        return '[NGProxy]' . $message;
    }
}
