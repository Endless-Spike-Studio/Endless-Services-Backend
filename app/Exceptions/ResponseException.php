<?php

namespace App\Exceptions;

class ResponseException extends BaseException
{
    protected string $log_channel = 'gdcn';
}
