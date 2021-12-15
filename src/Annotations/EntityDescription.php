<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class EntityDescription
{
    public $name;
    public $description;
    public $type;

    public $required;

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $required
     * @return EntityDescription
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    public function serialize()
    {
        return array(
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'required' => $this->required,
        );
    }

}