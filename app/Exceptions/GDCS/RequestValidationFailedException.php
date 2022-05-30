<?php

namespace App\Exceptions\GDCS;

use App\Enums\GDCS\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Validator;

class RequestValidationFailedException extends Exception
{
    protected Validator $validator;

    public function setValidator(Validator $validator): RequestValidationFailedException
    {
        $this->validator = $validator;
        return $this;
    }

    public function report(): void
    {
        $errors = $this->validator
            ->errors()
            ->toArray();

        Log::error(__('messages.gdcs_request_validate_failed'), [
            'errors' => $errors,
            'path' => Request::fullUrl()
        ]);
    }

    public function render(): int
    {
        $errors = $this->validator->errors();
        $firstError = $errors->first();

        if (is_numeric($firstError)) {
            return $firstError;
        }

        if (is_numeric($this->message)) {
            return $this->message;
        }

        return Response::REQUEST_VALIDATION_FAILED->value;
    }
}
