<?php

namespace DhiBlogBundle\Entity;

use DhiBlogBundle\Core\Entity\CoreEntity;
use DhiBlogBundle\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="blog_articles")
 */
class Article extends CoreEntity
{

    public const STATUTES = [
        "PUBLISHED" => "Publié",
        "DRAFT" => "Brouillon",
        "REVIEWING" => "En attente de rélecture",
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $published_at;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $pixel_code;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allow_comment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=ArticleComment::class, mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=ArticleCategory::class, mappedBy="article")
     */
    private $article_categories;

    /**
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     */
    private $last_content;

    private $build;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->article_categories = new ArrayCollection();
        $this->build = [
            'title' => null, 'content' => null, 'preview' => null,
            'slug' => null, 'tags' => null, 'summary' => null, 'pixel_code' => null,
        ];
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(?\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAllowComment(): ?bool
    {
        return $this->allow_comment;
    }

    public function setAllowComment(bool $allow_comment): self
    {
        $this->allow_comment = $allow_comment;

        return $this;
    }

    /**
     * @return Collection|ArticleComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(ArticleComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(ArticleComment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function addArticleCategory(ArticleCategory $articleCategory): self
    {
        if (!$this->article_categories->contains($articleCategory)) {
            $this->article_categories[] = $articleCategory;
            $articleCategory->setArticle($this);
        }

        return $this;
    }

    public function removeArticleCategory(ArticleCategory $articleCategory): self
    {
        if ($this->article_categories->removeElement($articleCategory)) {
            // set the owning side to null (unless already changed)
            if ($articleCategory->getArticle() === $this) {
                $articleCategory->setArticle(null);
            }
        }

        return $this;
    }

    public function inCategory(Category $category)
    {
        $yes = false;

        foreach ($this->getArticleCategories() as $index => $articleCategory) {
            if ($articleCategory->getCategory()->getId() == $category->getId()) {
                $yes = true;
                break;
            }
        }

        return $yes;
    }

    /**
     * @return Collection|ArticleCategory[]
     */
    public function getArticleCategories(): Collection
    {
        return $this->article_categories;
    }

    public function tagList()
    {
        $tags = json_decode($this->getTags(), true);

        return $tags;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function buildContentFromProperties()
    {
        $this->setLastContent(json_encode($this->build));
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        if ($this->isPublished())
            $this->title = $title;
        else
            $this->build['title'] = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        if ($this->isPublished())
            $this->content = $content;
        else
            $this->build['content'] = $content;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        if ($this->isPublished()){

            if ($preview)
                $this->preview = $preview;
        }
        else{
            $this->build['preview'] = $preview;
        }
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        if ($this->isPublished())
            $this->slug = $slug;
        else
            $this->build['slug'] = $slug;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        if ($this->isPublished())
            $this->summary = $summary;
        else
            $this->build['summary'] = $summary;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPixelCode()
    {
        return $this->pixel_code;
    }

    /**
     * @param string|null $pixel_code
     * @return Article
     */
    public function setPixelCode(?string $pixel_code)
    {
        if ($this->isPublished())
            $this->pixel_code = $pixel_code;
        else
            $this->build['pixel_code'] = $pixel_code;

        return $this;
    }

    public function buildPropertiesFromContent()
    {

        if ($this->getStatus() === self::STATUTES['PUBLISHED']) return;

        $build = json_decode($this->getLastContent(), true);

        if (isset($build['title']))
            $this->title = $build['title'];
        if (isset($build['content']))
            $this->content = $build['content'];
        if (isset($build['preview']))
            $this->preview = $build['preview'];
        if (isset($build['slug']))
            $this->slug = $build['slug'];
        if (isset($build['tags']))
            $this->tags = $build['tags'];
        if (isset($build['summary']))
            $this->summary = $build['summary'];
        if (isset($build['pixel_code']))
            $this->pixel_code = $build['pixel_code'];
    }

    /**
     * @return string|null
     */
    public function getLastContent()
    {
        return $this->last_content;
    }

    /**
     * @param string|null $last_content
     * @return Article
     */
    public function setLastContent($last_content)
    {
        $this->last_content = $last_content;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isPublished(): bool
    {
        return $this->getStatus() === self::STATUTES['PUBLISHED'];
    }
}
