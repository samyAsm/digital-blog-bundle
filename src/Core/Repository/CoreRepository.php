<?php
/**
 * Date: 15/03/21
 * Time: 09:29
 */

namespace DhiBlogBundle\Core\Repository;


use DhiBlogBundle\Core\Entity\CoreEntity;
use DhiBlogBundle\Core\Exceptions\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

abstract class CoreRepository extends ServiceEntityRepository
{
    protected $alias = 'a';

    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);

        $class = explode("\\", $this->getClassName());

        $this->alias = substr(strtolower($class[count($class) - 1]), 0,1);
    }

    protected function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderWithUnDeleted(){
        return $this->createQueryBuilder($this->getAlias())
            ->orderBy($this->getAlias().'.created_at', 'DESC')
            ->andWhere($this->getAlias().'.deleted_at IS NULL');
    }

    /**
     * @param string $uniq_key
     * @return mixed
     * @throws Alert
     */
    public function findByUniqKeyUnDeleted(?string $uniq_key = null)
    {
        try{
            return $this->getQueryBuilderWithUnDeleted()
                ->andWhere($this->alias.'.uniq_key = :uniq_key')
                ->setParameter('uniq_key', $uniq_key)
                ->getQuery()
                ->getOneOrNullResult();
        }catch (\Exception $e){
            throw new Alert($e->getMessage());
        }
    }

    /**
     * @param string $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByIdUnDeleted(?string $id = null)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere($this->alias.'.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \DateTime $dateTime
     * @return mixed
     */
    public function getAllCreatedBefore(\DateTime $dateTime)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere($this->alias.'.created_at < :target_date')
            ->setParameter('target_date', $dateTime)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $uniq_key
     * @return CoreEntity|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUniqKey(?string $uniq_key = null)
    {
        return $this->createQueryBuilder($this->alias)
            ->andWhere($this->alias.'.uniq_key = :uniq_key')
            ->setParameter('uniq_key', $uniq_key)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Collection
     */
    public final function getAllUnDeleted()
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public final function countAllUnDeleted()
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->select("count(".$this->alias.".id)")
            ->getQuery()
            ->getSingleScalarResult();
    }
}