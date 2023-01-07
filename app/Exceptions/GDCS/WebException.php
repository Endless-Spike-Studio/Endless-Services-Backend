<?php

namespace App\Exceptions\GDCS;

use App\Http\Traits\HasMessage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class WebException extends Exception
{
    use HasMessage;

    protected const FALLBACK_ROUTE = 'gdcs.home';

    public function __construct(protected $message, protected array $options = ['type' => 'error'])
    {
        parent::__construct($message);
    }

    public function report()
    {
        $this->pushMessage($this->message, $this->options);
    }

    public function render(): RedirectResponse
    {
        $currentAndPreviousIsSame = URL::previous() === URL::current();

        if ($currentAndPreviousIsSame && !Request::isMethodSafe()) {
            return to_route(static::FALLBACK_ROUTE);
        }

        return back();
    }
}
