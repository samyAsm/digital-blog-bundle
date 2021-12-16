<?php

namespace Dhi\BlogBundle\Services;

use Dhi\BlogBundle\Core\Entity\CoreEntity;
use Doctrine\Persistence\ManagerRegistry;

class ManagerService
{

    /**
     * @var KernelService
     */
    private $kernelService;

    public function __construct(KernelService $kernelService)
    {
        $this->kernelService = $kernelService;
    }

    /**
     * @return \Doctrine\Persistence\ObjectManager
     */
    public function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return ManagerRegistry
    */
    public function getDoctrine()
    {
        return $this->kernelService->getContainer()->get('doctrine');
    }

    /**
     * @param null $object
     * @return $this
     */
    public function flushData($object = null)
    {

        if ($object && $object instanceof CoreEntity) {
            $this->persistData($object);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this;
    }

    /**
     * @param $objects
     * @return $this
     */
    public function flushCollection($objects = [])
    {
        $this->persistCollection($objects);

        $this->flushData();

        return $this;
    }

    /**
     * @param $object
     * @return $this
     */
    public function persistData(CoreEntity $object)
    {
        if (method_exists($object, 'getId')) {
            if ($object->getId() === null) {
                $this->getDoctrine()->getManager()->persist($object);
            }
        }

        return $this;
    }

    /**
     * @param array $objects
     * @return $this
     */
    public function persistCollection($objects = [])
    {
        foreach ($objects as $object) {
            if ($object instanceof CoreEntity)
                $this->getDoctrine()->getManager()->persist($object);
        }

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function refreshData($data)
    {
        $this->getDoctrine()->getManager()->refresh($data);
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function deleteData($data = null)
    {
        if ($data) $this->getDoctrine()->getManager()->remove($data);

        return $this;
    }

}
