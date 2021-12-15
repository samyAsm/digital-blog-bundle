<?php

namespace Dhi\BlogBundle\Services;

use Dhi\BlogBundle\Entity\Article;
use Dhi\BlogBundle\Entity\ArticleComment;
use Dhi\BlogBundle\Entity\Author;
use Dhi\BlogBundle\Entity\Category;
use Dhi\BlogBundle\Repository\ArticleCommentRepository;
use Dhi\BlogBundle\Repository\ArticleRepository;
use Dhi\BlogBundle\Repository\AuthorRepository;
use Dhi\BlogBundle\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectRepository;

class RepositoryService extends ManagerService
{
    /**
     * @return ArticleRepository|ObjectRepository
    */
    public final function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }

    /**
     * @return CategoryRepository|ObjectRepository
    */
    public final function getCategoryRepository()
    {
        return $this->getDoctrine()->getRepository(Category::class);
    }

    /**
     * @return AuthorRepository|ObjectRepository
    */
    public final function getAuthorRepository()
    {
        return $this->getDoctrine()->getRepository(Author::class);
    }

    /**
     * @return ArticleCommentRepository|ObjectRepository
    */
    public final function getArticleCommentRepository()
    {
        return $this->getDoctrine()->getRepository(ArticleComment::class);
    }
}
