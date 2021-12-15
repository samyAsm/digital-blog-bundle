<?php
/**
 * Date: 16/03/21
 * Time: 16:44
 */

namespace Dhi\BlogBundle\Core\Subscriber;


use Dhi\BlogBundle\Core\Controller\AbstractRESTController;
use Dhi\BlogBundle\Services\KernelService;
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