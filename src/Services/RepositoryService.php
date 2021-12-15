<?php

namespace DhiBlogBundle\Services;

use DhiBlogBundle\Entity\Article;
use DhiBlogBundle\Entity\ArticleComment;
use DhiBlogBundle\Entity\Author;
use DhiBlogBundle\Entity\Category;
use DhiBlogBundle\Repository\ArticleCommentRepository;
use DhiBlogBundle\Repository\ArticleRepository;
use DhiBlogBundle\Repository\AuthorRepository;
use DhiBlogBundle\Repository\CategoryRepository;
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
