<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;


use Dhi\BlogBundle\Annotations\AuthorMustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Responses\Category\CategoryForm;
use Dhi\BlogBundle\Responses\Category\DeleteCategory;
use Dhi\BlogBundle\Services\Managers\CategoryManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/category", name="digital_blog_category_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @AuthorMustAuthenticate()
 */
class CategoryViewController extends CoreController
{

    /**
     * @Route(path="/edit/{category_id}", name="edit", methods={"GET"})
     *
     * @SWG\Post(description="Edit category")
     *
     * @SWG\Parameter(name="category_id", type="string", description="Category id",
     *     in="path", default="123456")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param CategoryManagerService $categoryManagerService
     * @return Response
     */
    public function edit(CategoryManagerService $categoryManagerService)
    {
        try {

            $category = $categoryManagerService->getCategoryFromRequest(false);

            $categories = $this->repositoryService->getCategoryRepository()->getAllUnDeleted();

            $this->setData($categories, 'categories');

            if ($category) $this->setData($category, 'category');

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new CategoryForm($this->getResponse());
    }

    /**
     * @Route(path="/delete/{category_id}", name="delete_form", methods={"GET"})
     *
     * @SWG\Post(description="Delete category")
     * @SWG\Parameter(name="category_id", type="string", description="Category id",
     *     in="path", default="123456")
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

            $this->setData($category, 'category');

            return new DeleteCategory($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return $this->badRequest();
    }
}