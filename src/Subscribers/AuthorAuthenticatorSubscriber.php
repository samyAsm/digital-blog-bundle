<?php


namespace DhiBlogBundle\Subscribers;


use DhiBlogBundle\Annotations\MustAuthenticate;
use DhiBlogBundle\Core\Controller\AbstractRESTController;
use DhiBlogBundle\Exceptions\NotAuthenticatedException;
use DhiBlogBundle\Services\AuthorAuthenticatorService;
use DhiBlogBundle\Services\KernelService;
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
     * @throws \App\Exceptions\InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \ReflectionException
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
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
     * @throws \App\Exceptions\InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
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
     * @throws \App\Exceptions\InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
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
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
     */
    private function processGuarder($annotation, \ReflectionMethod $method): void
    {

        if ($annotation instanceof MustAuthenticate) {

            $auth = $this->authenticatorService->getAuthor();

            $requested = $this->request->getSchemeAndHttpHost()
                . $this->request->getRequestUri();

            $m = strtoupper($this->request->getMethod());

            if ($m === "GET"
                && !$this->request->isXmlHttpRequest()
                && !preg_match("#(login|logout|notification|doc)#", $requested))
                $this->sessionInterface->set('requested', $requested);

            if (!$auth) {
                throw new NotAuthenticatedException("User not authenticated");
            }
        }
    }

    protected function getAnnotation(\ReflectionMethod $method)
    {
        $action = null;

        foreach ($this->reader->getMethodAnnotations($method) as $methodAnnotation) {
            if ($methodAnnotation instanceof MustAuthenticate) {
                $action = $methodAnnotation;
                break;
            }
        }

        return $action;
    }

}