<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;


use Dhi\BlogBundle\Annotations\MustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Responses\Author\AuthorForm;
use Dhi\BlogBundle\Responses\Author\DeleteAuthor;
use Dhi\BlogBundle\Services\Managers\AuthorManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/author", name="digital_blog_author_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @MustAuthenticate()
 */
class AuthorViewController extends CoreController
{

    /**
     * @Route(path="/edit/{author_id}", name="edit", methods={"GET"})
     *
     * @SWG\Post(description="Edit author")
     *
     * @SWG\Parameter(name="author_id", type="string", description="Author id",
     *     in="path", default="123456")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param AuthorManagerService $authorManagerService
     * @return Response
     */
    public function edit(AuthorManagerService $authorManagerService)
    {
        try {

            $author = $authorManagerService->getAuthorFromRequest(false);

            $categories = $this->repositoryService->getAuthorRepository()->getAllUnDeleted();

            $this->setData($categories, 'categories');

            if ($author) $this->setData($author, 'author');

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new AuthorForm($this->getResponse());
    }

    /**
     * @Route(path="/delete/{author_id}", name="delete_form", methods={"GET"})
     *
     * @SWG\Post(description="Delete author")
     * @SWG\Parameter(name="author_id", type="string", description="Author id",
     *     in="path", default="123456")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param AuthorManagerService $authorManagerService
     * @return Response
     */
    public function delete(AuthorManagerService $authorManagerService)
    {
        try {

            $author = $authorManagerService->getAuthorFromRequest();

            $this->setData($author, 'author');

            return new DeleteAuthor($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return $this->badRequest();
    }
}