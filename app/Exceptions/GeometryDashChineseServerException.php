<?php

namespace App\Exceptions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Throwable;

class GeometryDashChineseServerException extends BaseException
{
    protected string $log_channel = 'gdcn';

    public function __construct(
        string                    $message = null,
        int                       $code = 0,
        Throwable                 $previous = null,
        array                     $log_context = [],
        protected int|string|null $response = null
    )
    {
        parent::__construct($message, $code, $previous, 500, $log_context);
        $this->log_context = array_merge(['response' => $response], $this->log_context);
    }

    /**
     * @return HttpResponse|RedirectResponse|void
     */
    public function render()
    {
        if (!empty($this->response)) {
            return Response::make($this->response);
        }

        return parent::render();
    }

    protected function formatMessage(string $message): string
    {
        return '[GDCS]' . $message;
    }
}
