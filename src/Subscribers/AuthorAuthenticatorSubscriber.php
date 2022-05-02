<?php


namespace Dhi\BlogBundle\Subscribers;


use Dhi\BlogBundle\Annotations\AuthorAuthorMustAuthenticate;
use Dhi\BlogBundle\Core\Controller\AbstractRESTController;
use Dhi\BlogBundle\Exceptions\NotAuthenticatedOnBlogException;
use Dhi\BlogBundle\Services\AuthorAuthenticatorService;
use Dhi\BlogBundle\Services\KernelService;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class AuthorAuthenticatorSubscriber
{
    /**
     * @var Request
     */
    public $request;

    private $container;

    /**
     * @var AuthorAuthenticatorService
     */
    private $authenticatorService;

    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var SessionInterface
     */
    private $sessionInterface;

    public function __construct(KernelService $kernelService,
                                SessionInterface $sessionInterface,
                                Reader $reader,
                                AuthorAuthenticatorService $authenticatorService)
    {
        $this->container = $kernelService->getContainer();
        $this->authenticatorService = $authenticatorService;
        $this->reader = $reader;
        $this->sessionInterface = $sessionInterface;
    }

    /**
     * @param ControllerEvent $event
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \ReflectionException
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    public function onKernelController(ControllerEvent $event)
    {

        $this->request = $event->getRequest();

        if (!is_array($controller = $event->getController())) {
            return;
        }

        $c = $controller[0];

        $object = new \ReflectionObject($controller[0]);

        if ($c instanceof AbstractRESTController) {
            $method = $object->getMethod($controller[1]);
            $this->guardMethod($method);
            $this->guardClass($object, $method);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    private function guardMethod(\ReflectionMethod $method): void
    {
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            $this->processGuarder($annotation, $method);
        }
    }

    /**
     * @param \ReflectionObject $object
     * @param \ReflectionMethod $method
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    private function guardClass(\ReflectionObject $object, \ReflectionMethod $method): void
    {
        foreach ($this->reader->getClassAnnotations($object) as $annotation) {
            $this->processGuarder($annotation, $method);
        }
    }

    /**
     * @param $annotation
     * @param \ReflectionMethod $method
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    private function processGuarder($annotation, \ReflectionMethod $method): void
    {

        if ($annotation instanceof AuthorMustAuthenticate) {

            $auth = $this->authenticatorService->getAuthor();

            if (!$auth) {
                throw new NotAuthenticatedOnBlogException("User not authenticated");
            }
        }
    }

    protected function getAnnotation(\ReflectionMethod $method)
    {
        $action = null;

        foreach ($this->reader->getMethodAnnotations($method) as $methodAnnotation) {
            if ($methodAnnotation instanceof AuthorMustAuthenticate) {
                $action = $methodAnnotation;
                break;
            }
        }

        return $action;
    }

}