<?php
/**
 * Date: 14/04/21
 * Time: 11:01
 */

namespace Dhi\BlogBundle\Services\Managers;


use Dhi\BlogBundle\Core\Data\ValuesRetrieverTrait;
use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Entity\Article;
use Dhi\BlogBundle\Entity\ArticleCategory;
use Dhi\BlogBundle\Entity\Category;
use Dhi\BlogBundle\Services\DirectoryService;
use Dhi\BlogBundle\Services\FileUploadService;
use Dhi\BlogBundle\Services\ManagerService;
use Dhi\BlogBundle\Services\RepositoryService;
use Dhi\BlogBundle\Utils\StringMan;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleManagerService
{

    private $request;
    /**
     * @var RepositoryService
     */
    private $repositoryService;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ManagerService
     */
    private $managerService;
    /**
     * @var DirectoryService
     */
    private $directoryService;
    /**
     * @var FileUploadService
     */
    private $uploadService;

    use ValuesRetrieverTrait;

    /**
     * PaymentManagerService constructor.
     * @param RequestStack $requestStack
     * @param RepositoryService $repositoryService
     * @param ManagerService $managerService
     * @param TranslatorInterface $translator
     * @param DirectoryService $directoryService
     * @param FileUploadService $uploadService
     */
    public function __construct(RequestStack $requestStack,
                                RepositoryService $repositoryService,
                                ManagerService $managerService,
                                TranslatorInterface $translator,
                                DirectoryService $directoryService,
                                FileUploadService $uploadService)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repositoryService = $repositoryService;
        $this->translator = $translator;
        $this->managerService = $managerService;
        $this->directoryService = $directoryService;
        $this->uploadService = $uploadService;
    }

    /**
     * @param Article|null $article
     * @return Article|null
     * @throws \Exception
     */
    public function buildArticleFromRequest(?Article $article = null)
    {
        if (!$article) $article = new Article();

        $title = $this->request->get('title');
        $slug = $this->request->get('slug');
        $content = $this->request->get('content');
        $status = $this->request->get('status');
        $tags = $this->request->get('tags');
        $categories = $this->request->get('categories');
        $summary = $this->request->get('summary');
        $pixel_code = $this->request->get('pixel_code');
        $allow_comment = $this->getBool($this->request->get('allow_comment'));

        if (!strlen($title) < 3){
            throw new Alert(
                $this->translator->trans("Veuillez fournir un titre d'au moins 3 caractères")
            );
        }

        foreach ($article->getArticleCategories() as $index => $articleCategory) {
            $article->removeArticleCategory($articleCategory);
            $this->managerService->deleteData($articleCategory);
        }

        $article_categories = [];

        if (!is_array($categories)){
            throw new Alert(
                $this->translator->trans("Veuillez fournir au moins une catégorie")
            );
        }

        foreach ($categories as $index => $category) {

            /**
             * @var Category|null $cat
            */
            $cat = $this->repositoryService->getCategoryRepository()
                ->findByUniqKeyUnDeleted($category);

            if ($cat){
                $ac = new ArticleCategory();
                $cat->addArticleCategory($ac);
                $article->addArticleCategory($ac);
                $article_categories[] = $ac;
            }
        }

        if(!$slug) $slug = $title;

        $slug = (new StringMan())->slug($slug);

        $article
            ->setStatus(Article::STATUTES[$status])
            ->setTitle($title)
            ->setSlug($slug)
            ->setContent($content)
            ->setTags($tags)
            ->setSummary($summary)
            ->setPixelCode($pixel_code)
            ->setAllowComment($allow_comment)
        ;

        /**
         * @var UploadedFile $preview
         */
        $preview = $this->request->files->get('preview');

        $directory = $this->directoryService->getPublicArticleUploadDirectory();

        if ($preview) {
            $preview = $this->uploadService->storeUploadedFile(
                $preview,
                $directory,
                $preview->getClientOriginalName()
            );
        }

        $article->setPreview($preview);

        $article->buildContentFromProperties();

        return $article;
    }

    /**
     * @param bool|null $strict
     * @return Article|null
     * @throws Alert
     */
    public function getArticleFromRequest(?bool $strict = true): ?Article
    {

        $article_id = $this->request->get('article_id');

        /**
         * @var Article|null $article
         */
        $article = $this->repositoryService->getArticleRepository()
            ->findByUniqKeyUnDeleted($article_id);

        if ($strict && !$article)
            throw new Alert(
                $this->translator->trans("Veuillez fournir un article valide")
            );

        return $article;
    }
}