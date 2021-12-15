<?php


namespace DhiBlogBundle\Exceptions;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class InsufficientAccessException extends AccessDeniedHttpException
{

}