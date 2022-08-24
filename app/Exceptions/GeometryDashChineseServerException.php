<?php

namespace App\Exceptions;

class GeometryDashChineseServerException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function render()
    {
        if (!empty($this->code)) {
            return $this->code;
        }

        return parent::render();
    }

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
