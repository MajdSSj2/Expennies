<?php

namespace App\Exceptions;

use Throwable;

class ValidationException extends \RuntimeException
{

    public function __construct(
        public readonly array $errors,
        string $message = "validation error(s)",
        int $code = 422,
        Throwable $previous = null)
    {
        Parent::__construct($message, $code, $previous);
    }
}