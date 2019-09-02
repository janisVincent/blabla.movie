<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class AppException extends HttpException
{
    protected $statusCode = 400;

    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($this->statusCode, $message, $previous);
    }
}