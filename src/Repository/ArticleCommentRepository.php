<?php

namespace DhiBlogBundle\Repository;

use DhiBlogBundle\Core\Repository\CoreRepository;
use DhiBlogBundle\Entity\ArticleComment;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleComment[]    findAll()
 * @method ArticleComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCommentRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleComment::class);
    }
}
