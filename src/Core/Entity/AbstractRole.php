<?php
/**
 * Date: 15/03/21
 * Time: 11:02
 */

namespace DhiBlogBundle\Core\Entity;


use DhiBlogBundle\Annotations\PropertyDescription;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractRole extends CoreEntity
{

    /**
     * @PropertyDescription(
     *     name="slug",
     *     type="string",
     *     description="Identifiant unique d'un role de ce type du point de vue de l'utilisateur",
     *     required=true,
     *     size="20"
     * )
     * @ORM\Column(type="string", length=20)
     */
    protected $slug;

    /**
     * @PropertyDescription(
     *     name="description",
     *     type="string",
     *     description="Une description brève du rôle",
     *     required=true,
     *     size="155"
     * )
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    protected $description;


    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}