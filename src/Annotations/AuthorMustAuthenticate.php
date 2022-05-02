<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class AuthorMustAuthenticate
{
    public $description;

    public $name;

    /**
     * @param mixed $description
     * @return AuthorMustAuthenticate
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $name
     * @return AuthorMustAuthenticate
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function serialize()
    {
        return array(
            'name' => $this->name,
            'description' => $this->description
        );
    }
}