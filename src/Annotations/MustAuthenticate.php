<?php

namespace DhiBlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class MustAuthenticate
{
    public $description;

    public $name;

    /**
     * @param mixed $description
     * @return MustAuthenticate
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $name
     * @return MustAuthenticate
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