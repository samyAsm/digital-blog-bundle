<?php

namespace DhiBlogBundle\Entity;

use DhiBlogBundle\Core\Entity\CoreEntity;
use DhiBlogBundle\Repository\ArticleCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleCategoryRepository::class)
 * @ORM\Table(name="blog_article_categories")
 */
class ArticleCategory extends CoreEntity
{

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="article_categories")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="article_categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }
}
