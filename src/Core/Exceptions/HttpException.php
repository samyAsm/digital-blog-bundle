<?php
/**
 * Date: 15/03/21
 * Time: 16:35
 */

namespace Dhi\BlogBundle\Core\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message, $code);
    }

    public function __toString(): string
    {
        return "HttpException " . $this->code . ($this->message !== "" ? ": " . $this->message : "");
    }
}