<?php
/**
 * Date: 15/03/21
 * Time: 11:02
 */

namespace DhiBlogBundle\Core\Entity;


use DhiBlogBundle\Annotations\PropertyDescription;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractPerson extends CoreEntity
{

    /**
     * @PropertyDescription(
     *     name="last_name",
     *     type="string",
     *     description="Le prénom d'une personne (globalement)",
     *     required=false,
     *     size="20"
     * )
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $last_name;

    /**
     * @PropertyDescription(
     *     name="first_name",
     *     type="string",
     *     description="Le nom d'une personne (globalement)",
     *     required=false,
     *     size="20"
     * )
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $first_name;

    /**
     * @PropertyDescription(
     *     name="email",
     *     type="string",
     *     description="L'adresse email d'une personne (globalement)",
     *     required=false,
     *     size="100"
     * )
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @PropertyDescription(
     *     name="phone_number",
     *     type="string",
     *     description="Le numéro de téléphone d'une personne (globalement)",
     *     required=false,
     *     size="20"
     * )
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $phone_number;

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        if ($last_name) $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        if($first_name) $this->first_name = $first_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        if($email) $this->email = strtolower($email);

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        if ($phone_number) $this->phone_number = $phone_number;

        return $this;
    }

}