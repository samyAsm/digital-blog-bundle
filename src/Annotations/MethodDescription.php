<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class MethodDescription extends EntityDescription
{
    public $return = 'void';
    public $can_throw_exception = false;

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'can_throw_exception' => $this->can_throw_exception,
            'return' => $this->return
        ]);
    }
}