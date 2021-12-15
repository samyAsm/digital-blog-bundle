<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace DhiBlogBundle\Controller\Back;

use DhiBlogBundle\Annotations\MustAuthenticate;
use DhiBlogBundle\Core\Controller\CoreController;
use DhiBlogBundle\Core\Exceptions\Alert;
use DhiBlogBundle\Responses\Category\CategoryDeleted;
use DhiBlogBundle\Responses\Category\CategoryDeletionFail;
use DhiBlogBundle\Responses\Category\CategoryList;
use DhiBlogBundle\Responses\Category\CategoryStored;
use DhiBlogBundle\Responses\Category\CategoryStoreFail;
use DhiBlogBundle\Services\Managers\CategoryManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/category", name="digital_blog_category_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @MustAuthenticate()
 */
class CategoryController extends CoreController
{
    /**
     * @Route(path="/list", name="list", methods={"GET"})
     *
     * @SWG\Get(description="Get categories list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get categories list"
     * )
     *
     * @return Response
     */
    public function list()
    {
        try {

            $categories = $this->repositoryService->getCategoryRepository()
                ->getAllUnDeleted();

            $this->setData($categories, 'categories');

            return new CategoryList($this->getResponse());

        } catch (\Throwable $e) {

            $this->setMessage($e->getMessage());
        }

        return new CategoryList($this->getResponse());
    }

    /**
     *
     * @Route(path="/store", name="store", methods={"POST"})
     *
     * @SWG\Post(description="Create new category")
     *
     * @SWG\Parameter(name="category_id", type="string", description="Category's id",
     *     in="query", default="ca4a1329")
     * @SWG\Parameter(name="name", type="string", description="Category's name",
     *     in="query", default="Reource")
     * @SWG\Parameter(name="source", type="file", description="Category file",
     *     in="formData", default="")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param CategoryManagerService $categoryManagerService
     * @return Response
     */
    public function store(CategoryManagerService $categoryManagerService)
    {
        try {

            $category = $categoryManagerService->buildCategoryFromRequest(
                $categoryManagerService->getCategoryFromRequest(false)
            );

            $this->validate($category);

            $category->touch();

            $this->manager->flushData($category);

            $this->setData($category, 'category');

            return new CategoryStored($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new CategoryStoreFail($this->getResponse());
    }

    /**
     * @Route(path="/delete", name="delete", methods={"POST"})
     *
     * @SWG\Post(description="Delete category")
     * @SWG\Parameter(name="category_id", type="string", description="Category's id",
     *     in="query", default="ca4a1329")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param CategoryManagerService $categoryManagerService
     * @return Response
     */
    public function delete(CategoryManagerService $categoryManagerService)
    {
        try {

            $category = $categoryManagerService->getCategoryFromRequest();

            if ($category->getChildCategories())
                throw new Alert($this->translator->trans("Cette catégorie est utilisée"));

            $category->delete();

            $this->manager->flushData();

            $this->setData(true);

            return new CategoryDeleted($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new CategoryDeletionFail($this->getResponse());
    }
}