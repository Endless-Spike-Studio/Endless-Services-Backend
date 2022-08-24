<?php

namespace App\Exceptions;

use Throwable;

class GeometryDashChineseServerException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function __construct(
        string     $message = null,
        int        $code = 0,
        Throwable  $previous = null,
        array      $log_context = [],
        int|string $response = 0
    )
    {
        parent::__construct($message, $code, $previous, 500, $log_context);
    }

    public function render()
    {
        if (!empty($this->response)) {
            return $this->response;
        }

        return parent::render();
    }

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
