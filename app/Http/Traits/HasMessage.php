<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Session;

trait HasMessage
{
    public function pushErrorMessage(string $content, array $options = []): void
    {
        $this->pushMessage(
            $content,
            array_merge([
                'type' => 'error',
            ], $options)
        );
    }

    protected function pushMessage(string $content, array $options = []): void
    {
        Session::push('messages', [
            'content' => $content,
            'options' => $options,
        ]);
    }

    public function pushSuccessMessage(string $content, array $options = []): void
    {
        $this->pushMessage(
            $content,
            array_merge([
                'type' => 'success',
            ], $options)
        );
    }
}
