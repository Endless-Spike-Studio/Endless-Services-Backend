<?php

namespace App\Exceptions;

use App\Http\Traits\HasMessage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Throwable;

class BaseException extends Exception
{
    use HasMessage;

    public function __construct(
        string           $message = null,
        int              $code = 0,
        Throwable        $previous = null,
        public           $logging = true,
        public array     $log_context = [],
        public int       $http_code = 500,
        protected string $log_channel = 'stack',
        protected string $log_type = 'notice'
    )
    {
        parent::__construct($message, $code, $previous);

        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    public function setLogChannel(string $log_channel): void
    {
        if (!Config::has('logging.channels' . $log_channel)) {
            throw new InvalidArgumentException('日志频道不存在');
        }

        $this->log_channel = $log_channel;
    }

    public function report(): void
    {
        if ($this->logging) {
            $message = $this->formatMessage($this->message);
            Log::channel($this->log_channel)->log($this->log_type, $message, $this->log_context);
        }
    }

    protected function formatMessage(string $message): string
    {
        return $message;
    }

    /**
     * @return int|array|RedirectResponse|void
     */
    public function render()
    {
        /** @var int $code */
        $code = $this->getCode();
        $message = $this->formatMessage($this->message);

        if (is_int($code) && $code <= 0) {
            return $code;
        }

        if (Session::isStarted()) {
            $this->pushErrorMessage($message);

            return back();
        }

        if (Request::wantsJson()) {
            return ['error' => $message];
        }

        abort($this->http_code);
    }
}
