<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class PropertyDescription extends EntityDescription
{
    public $size;

    /**
     * @var boolean
    */
    public $unique = false;

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'size' => $this->size??'ND',
            'unique' => $this->unique
        ]);
    }
}