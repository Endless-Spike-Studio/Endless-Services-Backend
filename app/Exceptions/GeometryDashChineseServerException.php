<?php

namespace App\Exceptions;

use Throwable;

class GeometryDashChineseServerException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function __construct(
        string          $message = null,
        int             $code = 0,
        Throwable       $previous = null,
        array           $log_context = [],
        int|string|null $response = null
    )
    {
        parent::__construct($message, $code, $previous, 500, $log_context);
        $this->log_context = array_merge(['response' => $response], $this->log_context);
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
