<?php


namespace Dhi\BlogBundle\Exceptions;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthNotAuthorizedException extends AccessDeniedHttpException
{

}