<?php
/**
 * Date: 17/03/21
 * Time: 08:24
 */

namespace Dhi\BlogBundle\Services;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestServiceProvider
{
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return Request|InputBag|null
     */
    public function getRequest()
    {
        if ($this->request->getContent()) return $this->request;

        return $this->request->request;
    }
}