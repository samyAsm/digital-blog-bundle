<?php

namespace DhiBlogBundle\Entity;

use DhiBlogBundle\Core\Entity\AbstractUser;
use DhiBlogBundle\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\Table(name="blog_authors")
 */
class Author extends AbstractUser
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_publish;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_picture;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="author")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_password_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_password_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $token_expire_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_visit_at;

    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
    }

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(string $author_name): self
    {
        $this->author_name = $author_name;

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

    public function getCanPublish(): ?string
    {
        return $this->can_publish;
    }

    public function setCanPublish(string $can_publish): self
    {
        $this->can_publish = $can_publish;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?string $profile_picture): self
    {
        if ($profile_picture)
            $this->profile_picture = $profile_picture;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return Author
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpireAt(): ?\DateTimeInterface
    {
        return $this->token_expire_at;
    }

    public function setTokenExpireAt(?\DateTimeInterface $token_expire_at): self
    {
        $this->token_expire_at = $token_expire_at;

        return $this;
    }

    public function getLastVisitAt(): ?\DateTimeInterface
    {
        return $this->last_visit_at;
    }

    public function setLastVisitAt(?\DateTimeInterface $last_visit_at): self
    {
        $this->last_visit_at = $last_visit_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordCode()
    {
        return $this->reset_password_code;
    }

    /**
     * @param string|null $reset_password_code
     */
    public function setResetPasswordCode($reset_password_code): void
    {
        $this->reset_password_code = $reset_password_code;
    }

    /**
     * @param mixed $password
     * @return Author
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordToken()
    {
        return $this->reset_password_token;
    }

    /**
     * @param string|null $reset_password_token
     * @return Author
     */
    public function setResetPasswordToken(?string $reset_password_token)
    {
        $this->reset_password_token = $reset_password_token;

        return $this;
    }
}
