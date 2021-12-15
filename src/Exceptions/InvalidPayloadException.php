<?php


namespace DhiBlogBundle\Exceptions;

use Throwable;

class InvalidPayloadException extends \Exception
{
    public function __construct(string $message = "Invalid payload Exception", int $code = 0, Throwable $previous =
    null)
    {
        parent::__construct($message, $code, $previous);
    }
}