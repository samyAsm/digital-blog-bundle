<?php
/**
 * Date: 23/03/21
 * Time: 10:48
 */

namespace DhiBlogBundle\Validator;


use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class EntityValidator extends ConstraintValidatorFactory
{
    /**
     * @param string                       $className
     * @param ConstraintValidatorInterface $validator
     *
     * @return void
     */
    public function addValidator($className, $validator): void
    {
        $this->validators[$className] = $validator;
    }

}