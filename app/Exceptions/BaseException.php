<?php

namespace App\Exceptions;

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
    public array $logContext = [];
    public int $httpCode = 500;
    protected string $logChannel = 'stack';
    protected string $logType = 'notice';

    public function __construct(
        string                          $message = null,
        int                             $code = 0,
        Throwable                       $previous = null,
        int                             $httpCode = 500,
        array                           $logContext = [],
        public readonly int|string|null $gameResponse = null
    )
    {
        $this->httpCode = $httpCode;
        $this->logContext = array_merge(['game_response' => $this->gameResponse], Request::context(), $logContext);
        parent::__construct($message, $code, $previous);
    }

    public function report(): void
    {
        if ($this->logging) {
            $message = $this->formatMessage($this->message);
            Log::channel($this->logChannel)->log($this->logType, $message, $this->logContext);
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
            return Response::make($this->gameResponse);
        }

        if (Request::wantsJson()) {
            return Response::make(['error' => $message], $this->httpCode);
        }

        abort($this->httpCode, $message);
    }
}
