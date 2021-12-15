<?php
/**
 * Date: 14/04/21
 * Time: 11:01
 */

namespace DhiBlogBundle\Services\Managers;


use DhiBlogBundle\Core\Exceptions\Alert;
use DhiBlogBundle\Entity\Category;
use DhiBlogBundle\Services\DirectoryService;
use DhiBlogBundle\Services\FileUploadService;
use DhiBlogBundle\Services\RepositoryService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryManagerService
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
     * @var DirectoryService
     */
    private $directoryService;
    /**
     * @var FileUploadService
     */
    private $uploadService;

    /**
     * PaymentManagerService constructor.
     * @param RequestStack $requestStack
     * @param RepositoryService $repositoryService
     * @param TranslatorInterface $translator
     * @param DirectoryService $directoryService
     * @param FileUploadService $uploadService
     */
    public function __construct(RequestStack $requestStack,
                                RepositoryService $repositoryService,
                                TranslatorInterface $translator,
                                DirectoryService $directoryService,
                                FileUploadService $uploadService)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repositoryService = $repositoryService;
        $this->translator = $translator;
        $this->directoryService = $directoryService;
        $this->uploadService = $uploadService;
    }

    /**
     * @param Category|null $category
     * @return Category|null
     * @throws \Exception
     */
    public function buildCategoryFromRequest(?Category $category = null)
    {
        if (!$category) $category = new Category();

        $category_name = $this->request->get('category_name');
        $description = $this->request->get('description');
        $parent_category = $this->request->get('parent_category');

        $category
            ->setCategoryName($category_name)
            ->setDescription($description)
        ;

        $parent = $this->repositoryService->getCategoryRepository()->findByUniqKeyUnDeleted($parent_category);

        $category->setParentCategory($parent);

        /**
         * @var UploadedFile $preview
         */
        $preview = $this->request->files->get('preview');

        $directory = $this->directoryService->getPublicCategoryUploadDirectory();

        if ($preview) {
            $preview = $this->uploadService->storeUploadedFile(
                $preview,
                $directory,
                $preview->getClientOriginalName()
            );
        }

        $category->setPreview($preview);

        return $category;
    }

    /**
     * @param bool|null $strict
     * @return Category|null
     * @throws Alert
     */
    public function getCategoryFromRequest(?bool $strict = true): ?Category
    {

        $category_id = $this->request->get('category_id');

        /**
         * @var Category|null $category
         */
        $category = $this->repositoryService->getCategoryRepository()
            ->findByUniqKeyUnDeleted($category_id);

        if ($strict && !$category)
            throw new Alert(
                $this->translator->trans("Veuillez fournir une catégorie valide")
            );

        return $category;
    }
}