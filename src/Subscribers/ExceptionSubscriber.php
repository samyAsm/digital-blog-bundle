<?php

namespace Dhi\BlogBundle\Subscribers;

use Dhi\BlogBundle\Core\Data\APIResponse;
use Dhi\BlogBundle\Core\Subscriber\CoreSubscriber;
use Dhi\BlogBundle\Exceptions\NotAuthenticatedOnBlogException;
use Dhi\BlogBundle\Responses\Auth\RedirectToNotAuthenticatedResponse;
use Dhi\BlogBundle\Responses\Auth\RedirectToNotAuthorizedResponse;
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


        if ($event->getThrowable() instanceof NotAuthenticatedOnBlogException) {
            $event->setResponse(new RedirectToNotAuthenticatedResponse(new APIResponse()));
        }
    }
}
