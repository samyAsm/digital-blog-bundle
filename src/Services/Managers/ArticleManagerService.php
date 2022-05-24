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
use Dhi\BlogBundle\Services\RequestServiceProvider;
use Dhi\BlogBundle\Services\TranslatorProviderService;
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
     * @param RequestServiceProvider $requestServiceProvider
     * @param RepositoryService $repositoryService
     * @param ManagerService $managerService
     * @param TranslatorProviderService $translatorProviderService
     * @param DirectoryService $directoryService
     * @param FileUploadService $uploadService
     */
    public function __construct(RequestServiceProvider $requestServiceProvider,
                                RepositoryService $repositoryService,
                                ManagerService $managerService,
                                TranslatorProviderService $translatorProviderService,
                                DirectoryService $directoryService,
                                FileUploadService $uploadService)
    {
        $this->request = $requestServiceProvider->getRequest();
        $this->repositoryService = $repositoryService;
        $this->translator = $translatorProviderService->getTranslator();
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

        $title = $this->request->request->get('title');
        $slug = $this->request->request->get('slug');
        $content = $this->request->request->get('content');
        $status = $this->request->request->get('status');
        $tags = $this->request->request->get('tags');
        $categories = $this->request->request->get('categories');
        $summary = $this->request->request->get('summary');
        $pixel_code = $this->request->request->get('pixel_code');
        $allow_comment = $this->getBool($this->request->request->get('allow_comment'));

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

        $article_id = $this->request->request->get('article_id');

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