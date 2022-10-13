<?php

namespace App\Exceptions;

use App\Exceptions\GDCS\WebException;
use App\Http\Traits\HasMessage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

class BaseException extends Exception
{
    use HasMessage;

    public bool $logging = true;
    public array $log_context = [];
    public int $http_code = 500;
    protected string $log_channel = 'stack';
    protected string $log_type = 'notice';

    public function __construct(
        string                          $message = null,
        int                             $code = 0,
        Throwable                       $previous = null,
        int                             $http_code = 500,
        array                           $log_context = [],
        public readonly int|string|null $game_response = null
    )
    {
        $this->http_code = $http_code;
        $this->log_context = array_merge(['game_response' => $this->game_response], Request::context(), $log_context);
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
     * @return HttpResponse|RedirectResponse|void
     * @throws WebException
     */
    public function render()
    {
        $message = $this->formatMessage($this->message);

        if (Request::hasHeader('X-Inertia')) {
            throw new WebException($message);
        }

        if (Request::has('secret')) {
            return Response::make($this->game_response);
        }

        if (Request::wantsJson()) {
            return Response::make(['error' => $message], $this->http_code);
        }

        abort($this->http_code, $message);
    }
}
