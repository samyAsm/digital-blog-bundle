<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Guarder
{
    public $store = false;

    /**
     * @return bool
     */
    public function isStore(): bool
    {
        return $this->store;
    }

    /**
     * @param bool $store
     */
    public function setStore(bool $store): void
    {
        $this->store = $store;
    }

}