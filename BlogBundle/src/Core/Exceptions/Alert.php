<?php
/**
 * Date: 15/03/21
 * Time: 16:35
 */

namespace Dhi\BlogBundle\Core\Exceptions;


use Exception;
use Throwable;

class Alert extends Exception
{
    public function __construct(string $message = "Alert", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}