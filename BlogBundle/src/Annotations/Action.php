<?php

namespace Dhi\BlogBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Action
{
    const GROUPS = [
        "ADMINISTRATION" => "ADMINISTRATION",
        "COMPANY" => "COMPANY",
        "TERMINAL" => "TERMINAL",
    ];

    public $description;

    public $name;

    public $group;

    /**
     * @var array $rules
    */
    public $rules = [
        "ADMINISTRATOR" =>"ADMINISTRATOR"
    ];

    /**
     * @param mixed $description
     * @return Action
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $name
     * @return Action
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $group
     * @return Action
     */
    public function setGroup(?array $group)
    {
        $this->group = $group;
        return $this;
    }

    public function serialize()
    {
        return array(
            'name' => $this->name,
            'description' => $this->description,
            'group' => $this->group,
            'rules' => $this->rules,
        );
    }

    /**
     * @param array $rules
     * @return Action
     */
    public function setRules(array $rules): Action
    {
        $this->rules = $rules;
        return $this;
    }

}