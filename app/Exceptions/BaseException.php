<?php

namespace App\Exceptions;

use App\Http\Traits\HasMessage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Throwable;

class BaseException extends Exception
{
    use HasMessage;

    public bool $logging = true;
    public array $log_context = [];
    public int $http_code = 500;
    protected string $log_channel = 'stack';
    protected string $log_type = 'notice';

    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, int $http_code = 500, array $log_context = [])
    {
        $this->http_code = $http_code;
        $this->log_context = array_merge(Request::context(), $log_context);
        parent::__construct($message, $code, $previous);
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
        $message = method_exists($this, 'formatMessage') ? $this->formatMessage($this->message) : $this->message;

        if (Session::isStarted()) {
            $this->pushErrorMessage($message);
            return back();
        }

        if (Request::wantsJson()) {
            return Response::make(['error' => $message], $this->http_code);
        }

        abort($this->http_code, $message);
    }
}
