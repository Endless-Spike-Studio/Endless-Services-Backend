<?php

namespace App\Exceptions;

use Throwable;

class GeometryDashChineseServerException extends BaseException
{
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, public array $log_context = [], protected ?int $response_code = null)
    {
        parent::__construct($message, $code, $previous, log_channel: 'gdcn');
    }

    public function render()
    {
        if (!empty($this->response_code)) {
            return $this->response_code;
        }

        return parent::render();
    }

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
