<?php
/**
 * Date: 21/03/21
 * Time: 08:26
 */

namespace DhiBlogBundle\Twig;

use DhiBlogBundle\Services\AuthorAuthenticatorService;
use DhiBlogBundle\Services\EnvService;
use DhiBlogBundle\Services\RepositoryService;
use DhiBlogBundle\Utils\KernelUtils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppTwigExtension extends AbstractExtension
{

    /**
     * @var EnvService
     */
    private $env;

    /**
     * @var RepositoryService
     */
    private $repository;

    /**
     * @var Environment
     */
    protected $twig;
    /**
     * @var AuthorAuthenticatorService
     */
    private $authenticatorService;

    /**
     * AppTwigExtension constructor.
     * @param EnvService $env
     * @param RepositoryService $repository
     * @param AuthorAuthenticatorService $authenticatorService
     */
    public function __construct(EnvService $env,
                                RepositoryService $repository,
                                AuthorAuthenticatorService $authenticatorService)
    {
        $this->env = $env;
        $this->repository = $repository;
        $c = KernelUtils::getKernel()->getContainer();
        $this->twig = $c->get('twig');
        $this->authenticatorService = $authenticatorService;
    }

    /**
     * {@inheritdoc}
     *
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('author', [$this, 'author']),
            new TwigFunction('parent_blog_categories', [$this, 'parentBlogCategories']),
            new TwigFunction('blog_categories', [$this, 'blogCategories']),
            new TwigFunction('blog_latest_articles', [$this, 'blogLatestArticles']),
            new TwigFunction('home_latest_articles', [$this, 'homeLatestArticles']),
            new TwigFunction('template_exists', [$this, 'templateExists']),
        ];
    }

    public function parentBlogCategories()
    {
        return $this->repository->getCategoryRepository()->getAllParentCategories();
    }

    public function blogCategories()
    {
        return $this->repository->getCategoryRepository()->getAllUnDeleted();
    }

    public function blogLatestArticles()
    {
        return $this->repository->getArticleRepository()->getLatest();
    }

    public function homeLatestArticles()
    {
        return $this->repository->getArticleRepository()->getLatest(3);
    }

    public function author()
    {
        return $this->authenticatorService->getAuthor();
    }

    public function templateExists($template)
    {
        try{
            $this->twig->render($template);
        }catch (\Throwable $throwable){
            return false;
        }

        return true;
    }
}