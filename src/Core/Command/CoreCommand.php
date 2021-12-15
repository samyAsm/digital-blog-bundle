<?php
/**
 * Date: 17/03/21
 * Time: 13:21
 */

namespace DhiBlogBundle\Core\Command;

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
        $this->manager = $this->container->get('app.manager_service');
        $this->repositoryService = $this->container->get('app.repository_service');
        $this->env = $env;
    }
}