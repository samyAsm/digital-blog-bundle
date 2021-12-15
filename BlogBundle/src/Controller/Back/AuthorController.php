<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;

use Dhi\BlogBundle\Annotations\MustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Responses\Author\AuthorDeleted;
use Dhi\BlogBundle\Responses\Author\AuthorDeletionFail;
use Dhi\BlogBundle\Responses\Author\AuthorList;
use Dhi\BlogBundle\Responses\Author\AuthorStored;
use Dhi\BlogBundle\Responses\Author\AuthorStoreFail;
use Dhi\BlogBundle\Services\MailingService;
use Dhi\BlogBundle\Services\Managers\AuthorManagerService;
use Dhi\BlogBundle\Utils\RandomUtils;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/author", name="digital_blog_author_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @MustAuthenticate()
 */
class AuthorController extends CoreController
{

    use RandomUtils;

    /**
     * @Route(path="/list", name="list", methods={"GET"})
     *
     * @SWG\Get(description="Get authors list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get authors list"
     * )
     *
     * @return Response
     */
    public function list()
    {
        try {

            $authors = $this->repositoryService->getAuthorRepository()
                ->getAllUnDeleted();

            $this->setData($authors, 'authors');

            return new AuthorList($this->getResponse());

        } catch (\Throwable $e) {

            $this->setMessage($e->getMessage());
        }

        return new AuthorList($this->getResponse());
    }

    /**
     *
     * @Route(path="/store", name="store", methods={"POST"})
     *
     * @SWG\Post(description="Create new author")
     *
     * @SWG\Parameter(name="author_id", type="string", description="Author's id",
     *     in="query", default="ca4a1329")
     * @SWG\Parameter(name="name", type="string", description="Author's name",
     *     in="query", default="Reource")
     * @SWG\Parameter(name="source", type="file", description="Author file",
     *     in="formData", default="")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param AuthorManagerService $authorManagerService
     * @param MailingService $mailingService
     * @return Response
     */
    public function store(AuthorManagerService $authorManagerService,
                          MailingService $mailingService)
    {
        try {

            $author = $authorManagerService->buildAuthorFromRequest(
                $authorManagerService->getAuthorFromRequest(false)
            );

            $this->validate($author);

            $author->setResetPasswordCode($this->getRandomCode(4));

            $mailingService->sendResetPasswordCode($author);

            $author->touch();

            $this->manager->flushData($author);

            $this->setData($author, 'author');

            return new AuthorStored($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new AuthorStoreFail($this->getResponse());
    }

    /**
     * @Route(path="/delete", name="delete", methods={"POST"})
     *
     * @SWG\Post(description="Delete author")
     * @SWG\Parameter(name="author_id", type="string", description="Author's id",
     *     in="query", default="ca4a1329")
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

            $author->delete();

            $this->manager->flushData();

            $this->setData(true);

            return new AuthorDeleted($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new AuthorDeletionFail($this->getResponse());
    }
}