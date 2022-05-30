<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Session;

trait HasMessage
{
    protected function message(string $content, array $options = []): void
    {
        Session::push('messages', [
            'content' => $content,
            'options' => $options
        ]);
    }
}
