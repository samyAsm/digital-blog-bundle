<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 02/12/21
 * Time: 14:57
 */

namespace DhiBlogBundle\Services;


class ConfigService
{

    protected $configs = [];

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }
}