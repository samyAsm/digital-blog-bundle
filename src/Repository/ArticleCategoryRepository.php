<?php

namespace DhiBlogBundle\Repository;

use DhiBlogBundle\Core\Repository\CoreRepository;
use DhiBlogBundle\Entity\ArticleCategory;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCategory[]    findAll()
 * @method ArticleCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCategoryRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCategory::class);
    }
}
