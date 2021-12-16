<?php


namespace Dhi\BlogBundle\Exceptions;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class InsufficientAccessException extends AccessDeniedHttpException
{

}