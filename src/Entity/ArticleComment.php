<?php

namespace Dhi\BlogBundle\Entity;

use Dhi\BlogBundle\Core\Entity\CoreEntity;
use Dhi\BlogBundle\Repository\ArticleCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleCommentRepository::class)
 * @ORM\Table(name="blog_comments")
 */
class ArticleComment extends CoreEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     */
    private $article;

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(string $author_name): self
    {
        $this->author_name = $author_name;

        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->author_email;
    }

    public function setAuthorEmail(string $author_email): self
    {
        $this->author_email = $author_email;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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
