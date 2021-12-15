<?php
/**
 * Date: 16/03/21
 * Time: 16:44
 */

namespace DhiBlogBundle\Core\Subscriber;


use DhiBlogBundle\Core\Controller\AbstractRESTController;
use DhiBlogBundle\Services\KernelService;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class CoreSubscriber extends AbstractRESTController implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(KernelService $kernelService, Reader $reader)
    {
        parent::__construct($kernelService);

        $this->reader = $reader;
    }
}