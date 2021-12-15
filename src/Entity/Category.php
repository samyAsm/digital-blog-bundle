<?php

namespace Dhi\BlogBundle\Entity;

use Dhi\BlogBundle\Core\Entity\CoreEntity;
use Dhi\BlogBundle\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="blog_categories")
 */
class Category extends CoreEntity
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ArticleCategory::class, mappedBy="category")
     */
    private $article_categories;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="child_categories")
     */
    private $parent_category;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent_category")
     */
    private $child_categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    public function __construct()
    {
        parent::__construct();
        $this->article_categories = new ArrayCollection();
        $this->child_categories = new ArrayCollection();
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ArticleCategory[]
     */
    public function getArticleCategories(): Collection
    {
        return $this->article_categories;
    }

    public function addArticleCategory(ArticleCategory $articleCategory): self
    {
        if (!$this->article_categories->contains($articleCategory)) {
            $this->article_categories[] = $articleCategory;
            $articleCategory->setCategory($this);
        }

        return $this;
    }

    public function removeArticleCategory(ArticleCategory $articleCategory): self
    {
        if ($this->article_categories->removeElement($articleCategory)) {
            // set the owning side to null (unless already changed)
            if ($articleCategory->getCategory() === $this) {
                $articleCategory->setCategory(null);
            }
        }

        return $this;
    }

    public function childOf(Category $category)
    {
        if (!$this->getParentCategory()) return false;

        return $this->getParentCategory()->getId() === $category->getId();
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function getParentCategory(): ?self
    {
        return $this->parent_category;
    }

    public function setParentCategory(?self $parent_category): self
    {
        $this->parent_category = $parent_category;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildCategories(): Collection
    {
        return $this->child_categories;
    }

    public function addChildCategory(self $childCategory): self
    {
        if (!$this->child_categories->contains($childCategory)) {
            $this->child_categories[] = $childCategory;
            $childCategory->setParentCategory($this);
        }

        return $this;
    }

    public function removeChildCategory(self $childCategory): self
    {
        if ($this->child_categories->removeElement($childCategory)) {
            // set the owning side to null (unless already changed)
            if ($childCategory->getParentCategory() === $this) {
                $childCategory->setParentCategory(null);
            }
        }

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        if ($preview)
            $this->preview = $preview;

        return $this;
    }
}
