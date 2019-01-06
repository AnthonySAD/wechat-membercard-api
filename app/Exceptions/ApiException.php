<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException    
{
    public function __construct(int $errorCode, string $message = null, \Exception $previous = null, array $headers = array())
    {
        parent::__construct(
            ErrorCodes::getStatusCode($errorCode),
            $message ? $message : ErrorCodes::getMessage($errorCode),
            $previous,
            $headers,
            $errorCode
        );
    }
}
