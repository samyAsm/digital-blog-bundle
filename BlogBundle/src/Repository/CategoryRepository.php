<?php

namespace Dhi\BlogBundle\Repository;

use Dhi\BlogBundle\Core\Repository\CoreRepository;
use Dhi\BlogBundle\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function getAllParentCategories()
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('c.parent_category IS NULL')
            ->getQuery()
            ->getResult();
    }
}
