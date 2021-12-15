<?php

namespace DhiBlogBundle\Subscribers;

use DhiBlogBundle\Core\Data\APIResponse;
use DhiBlogBundle\Core\Subscriber\CoreSubscriber;
use DhiBlogBundle\Exceptions\NotAuthenticatedException;
use DhiBlogBundle\Responses\Auth\RedirectToNotAuthenticatedResponse;
use DhiBlogBundle\Responses\Auth\RedirectToNotAuthorizedResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['onKernelException', 100]],
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        //TODO: Redirect to controller which will render custom responses


        if ($event->getThrowable() instanceof MethodNotAllowedHttpException) {
            $event->setResponse(new RedirectToNotAuthorizedResponse(new APIResponse()));
        } elseif ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse(new RedirectToNotAuthorizedResponse(new APIResponse()));
        } elseif ($event->getThrowable() instanceof NotAuthenticatedException) {
            $event->setResponse(new RedirectToNotAuthenticatedResponse(new APIResponse()));
        } else {
            $event->setResponse(new RedirectToNotAuthorizedResponse(new APIResponse()));
        }
    }
}
