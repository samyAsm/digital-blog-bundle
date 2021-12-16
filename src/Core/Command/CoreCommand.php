<?php
/**
 * Date: 17/03/21
 * Time: 13:21
 */

namespace Dhi\BlogBundle\Core\Command;

use Dhi\BlogBundle\Services\EnvService;
use Dhi\BlogBundle\Services\KernelService;
use Dhi\BlogBundle\Services\ManagerService;
use Dhi\BlogBundle\Services\RepositoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class CoreCommand extends Command
{
    /**
     * @var Kernel|KernelInterface|null $kernel
    */
    protected $kernel;

    protected $container;

    /**
     * @var ManagerService|null $manager
    */
    protected $manager;

    /**
     * @var RepositoryService|null $repositoryService
     */
    protected $repositoryService;
    /**
     * @var EnvService
     */
    protected $env;

    public function __construct(EnvService $env, string $name = null,  KernelService $kernelService = null)
    {
        parent::__construct($name);
        $this->kernel = $kernelService->getKernel();
        $this->container = $this->kernel->getContainer();
        $this->manager = $this->container->get('dhi_blog_service.manager_service');
        $this->repositoryService = $this->container->get('dhi_blog_service.repository_service');
        $this->env = $env;
    }
}