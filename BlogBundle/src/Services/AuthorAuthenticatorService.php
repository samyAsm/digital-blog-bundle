<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 04/12/21
 * Time: 17:00
 */

namespace Dhi\BlogBundle\Services;


use Dhi\BlogBundle\Annotations\Auth;
use Dhi\BlogBundle\Entity\Author;
use Dhi\BlogBundle\Utils\DateUtil;
use Dhi\BlogBundle\Utils\RandomUtils;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthorAuthenticatorService extends ManagerService
{

    use RandomUtils;

    /**
     * @var SessionInterface
     */
    private $cookie_manager;
    /**
     * @var EnvService
     */
    private $env;
    /**
     * @var RepositoryService
     */
    private $repositoryService;

    /**
     * @var Author|null
    */
    private $author;

    public function __construct(KernelService $kernelService,
                                SessionInterface $sessionInterface,
                                EnvService $env,
                                RepositoryService $repositoryService)
    {
        parent::__construct($kernelService);
        $this->cookie_manager = $sessionInterface;
        $this->env = $env;
        $this->repositoryService = $repositoryService;
    }

    /**
     * @param Author $author
     * @throws \Exception
     */
    public function openSession(Author $author)
    {
        $author->setToken(base64_encode($this->getUuid()))
            ->setTokenExpireAt(DateUtil::addHoursToDate(DateUtil::getDate(),
                $this->env->getParam('SESSION_LIFE_TIME', 60)));

        $this->cookie_manager->set($this->getTokenParam(), $author->getToken());
    }

    /**
     * @return Author|null
     */
    public function getAuthor()
    {
        try{
            if (!$this->author){
                try{
                    $this->author = $this->repositoryService->getAuthorRepository()
                        ->findByToken($this->cookie_manager->get($this->getTokenParam()));

                }catch (NonUniqueResultException $uniqueResultException){
                    $this->author = null;
                }
            }
        }catch (\Throwable $throwable){}

        return $this->author;
    }

    /**
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     * @throws \Exception
     */
    public function closeSession()
    {
        $this->getAuthor()->setToken(null)->setTokenExpireAt(DateUtil::getDate());
        $this->cookie_manager->remove($this->getTokenParam());
    }

    /**
     * @return mixed
     * @throws \Dhi\BlogBundle\Exceptions\InvalidArgumentException
     */
    protected function getTokenParam(): ?string
    {
        return $this->env->getParam('SESSION_WEB_TOKEN', null);
    }
}