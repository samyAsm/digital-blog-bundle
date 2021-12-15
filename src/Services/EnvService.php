<?php
/**
 * Date: 06/10/20
 * Time: 07:30
 */

namespace DhiBlogBundle\Services;


use DhiBlogBundle\Exceptions\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnvService extends AbstractController
{

    public function isDevEnv(){

        return $_ENV['APP_ENV'] == "dev";
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getDeveloperEmail(){

        return $this->getParam("DEVELOPER_EMAIL");
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getDeveloperPhone(){

        return $this->getParam("DEVELOPER_PHONE");
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getAppName(){

        return $this->getParam("APP_NAME", "Application");
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getParam($key, $default = null)
    {

        if (!isset($_ENV) && $default)
            return $default;

        if (!isset($_ENV))
            throw new InvalidArgumentException("Env parameter not found : ".$key);

        return $_ENV[$key];
    }
}