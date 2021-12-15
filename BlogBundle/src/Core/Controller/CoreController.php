<?php
/**
 * Date: 15/03/21
 * Time: 09:28
 */

namespace Dhi\BlogBundle\Core\Controller;

use Dhi\BlogBundle\Core\Data\ValuesRetrieverTrait;
use Dhi\BlogBundle\Core\Entity\CoreEntity;
use Dhi\BlogBundle\Services\KernelService;
use Dhi\BlogBundle\Utils\RegexUtils;

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