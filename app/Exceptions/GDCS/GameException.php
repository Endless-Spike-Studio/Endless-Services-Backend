<?php

namespace App\Exceptions\GDCS;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Request;
use Throwable;

class GameException extends BaseException
{
    public function __construct(
        string           $message = null,
        int              $code = 0,
        Throwable        $previous = null,
        public           $logging = true,
        public array     $log_context = [],
        public int       $http_code = 500,
        protected string $log_channel = 'gdcn',
        protected string $log_type = 'notice',
        protected ?int   $response_code = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->log_context = array_merge(Request::context(), $this->log_context);
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
        return 'GDCS 游戏异常: ' . $message;
    }
}
