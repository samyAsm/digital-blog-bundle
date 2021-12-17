<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 16/12/21
 * Time: 12:48
 */

namespace Dhi\BlogBundle\Command;


use Dhi\BlogBundle\Core\Command\CoreCommand;
use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Entity\Author;
use Dhi\BlogBundle\Services\EnvService;
use Dhi\BlogBundle\Services\KernelService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class InstallCommand extends CoreCommand
{
    protected static $defaultName = 'digital:blog:setup';

    public function __construct(EnvService $env, KernelService $kernelService = null)
    {
        $name = self::$defaultName;

        parent::__construct($env, $name, $kernelService);
    }

    /**
     * @var SymfonyStyle|null $io
     */
    private $io;

    protected function configure()
    {
        $this->setDescription("Initialize app \n Setting rules, and the first super admin user");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        try {

            $this->io->title('Initializing blog admin');

            $email = $this->env->getParam('DIGITAL_BLOG_ADMIN_EMAIL', null);
            $name = $this->env->getParam('DIGITAL_BLOG_ADMIN_NAME', "admin");
            $password = $this->env->getParam('DIGITAL_BLOG_ADMIN_PASSWORD', null);

            if (!$email || !$password)
                throw new Alert("Provide DIGITAL_BLOG_ADMIN_EMAIL and DIGITAL_BLOG_ADMIN_PASSWORD");

            $author = $this->repositoryService->getAuthorRepository()
                ->findByCredentials($email);

            if ($author)
                throw new Alert("There is one author with this email");

            $author = new Author();

            $author->setEmail($email);

            $author->setAuthorName($name);

            $author->setDescription($name);

            $author->setCanPublish(true);

            $author->setPassword($this->container->get('dhi_blog_service.password_service')
                ->encodePassword($author, $password));

            $this->manager->flushData($author);

            $this->io->success("Finished admin initialization");

        } catch (Throwable $e) {
            $this->io->error($e->getMessage());
        }

        return 0;
    }
}