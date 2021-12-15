<?php
/**
 * Date: 15/03/21
 * Time: 09:28
 */

namespace DhiBlogBundle\Core\Controller;

use DhiBlogBundle\Core\Data\ValuesRetrieverTrait;
use DhiBlogBundle\Core\Entity\CoreEntity;
use DhiBlogBundle\Services\KernelService;
use DhiBlogBundle\Utils\RegexUtils;

abstract class CoreController extends AbstractRESTController
{

    use RegexUtils;


    use ValuesRetrieverTrait;

    /**
     * CoreController constructor.
     * @param KernelService $kernelService
     */
    public function __construct(KernelService $kernelService)
    {
        parent::__construct($kernelService);
    }

    /**
     * @param CoreEntity[] $entities
     */
    protected function validateCollection($entities)
    {
        foreach ($entities as $ek => $entity) {
            $this->validate($entity);
        }
    }
}