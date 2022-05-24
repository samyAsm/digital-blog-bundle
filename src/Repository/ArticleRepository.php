<?php

namespace Dhi\BlogBundle\Repository;

use Dhi\BlogBundle\Core\Repository\CoreRepository;
use Dhi\BlogBundle\Entity\Article;
use Dhi\BlogBundle\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param $search
     * @return Article[]
     */
    public function search($search)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('a.title LIKE :t OR a.content LIKE :t OR a.tags LIKE :t')
            ->setParameter('t', '%' . addcslashes($search, '%_') . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Category $category
     * @return Article[]
     */
    public function getAllByCategory(Category $category)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->leftJoin('a.article_categories', 'article_categories')
            ->leftJoin('article_categories.category', 'category')
            ->andWhere('category.id = :category_id')
            ->setParameter('category_id', $category->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $max
     * @param $status
     * @return Article[]
     */
    public function getLatest($max = 5, $status = Article::STATUTES['PUBLISHED'])
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('a.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->setMaxResults($max)
            ->getResult();
    }
}
