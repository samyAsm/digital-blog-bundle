<?php

namespace Dhi\BlogBundle\Repository;

use Dhi\BlogBundle\Core\Repository\CoreRepository;
use Dhi\BlogBundle\Entity\ArticleComment;
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
