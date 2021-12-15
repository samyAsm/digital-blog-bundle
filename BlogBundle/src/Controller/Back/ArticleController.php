<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;


use Dhi\BlogBundle\Annotations\MustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Responses\Article\ArticleDeleted;
use Dhi\BlogBundle\Responses\Article\ArticleDeletionFail;
use Dhi\BlogBundle\Responses\Article\ArticleList;
use Dhi\BlogBundle\Responses\Article\ArticleSearch;
use Dhi\BlogBundle\Responses\Article\ArticleStored;
use Dhi\BlogBundle\Responses\Article\ArticleStoreFail;
use Dhi\BlogBundle\Services\Managers\ArticleManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route(path="/api/article", name="digital_blog_article_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @MustAuthenticate()
 */
class ArticleController extends CoreController
{
    /**
     * @Route(path="/list", name="list", methods={"GET"})
     *
     * @SWG\Get(description="Get articles list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get articles list"
     * )
     *
     * @return Response
     */
    public function list()
    {
        try {

            $articles = $this->repositoryService->getArticleRepository()
                ->getAllUnDeleted();

            $this->setData($articles, 'articles');

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleList($this->getResponse());
    }

    /**
     * @Route(path="/search", name="search", methods={"GET"})
     *
     * @SWG\Get(description="Get articles search")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get articles search"
     * )
     *
     * @return Response
     */
    public function search()
    {
        try {

            $articles = $this->repositoryService->getArticleRepository()
                ->search($this->request->get('search'));

            $this->setData($articles, 'articles');

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleSearch($this->getResponse());
    }

    /**
     *
     * @Route(path="/store", name="store", methods={"POST"})
     *
     * @SWG\Post(description="Create new article")
     *
     * @SWG\Parameter(name="article_id", type="string", description="Article's id",
     *     in="query", default="ca4a1329")
     * @SWG\Parameter(name="name", type="string", description="Article's name",
     *     in="query", default="Reource")
     * @SWG\Parameter(name="source", type="file", description="Article file",
     *     in="formData", default="")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleManagerService $articleManagerService
     * @return Response
     */
    public function store(ArticleManagerService $articleManagerService)
    {
        try {

            $article = $articleManagerService->buildArticleFromRequest(
                $articleManagerService->getArticleFromRequest(false)
            );

            $this->validate($article);

            $article->touch();

            $this->manager->persistCollection($article->getArticleCategories());

            $this->manager->flushData($article);

            $this->setData($article, 'article');

            return new ArticleStored($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleStoreFail($this->getResponse());
    }

    /**
     * @Route(path="/delete", name="delete", methods={"POST"})
     *
     * @SWG\Post(description="Delete article")
     * @SWG\Parameter(name="article_id", type="string", description="Article's id",
     *     in="query", default="ca4a1329")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleManagerService $articleManagerService
     * @return Response
     */
    public function delete(ArticleManagerService $articleManagerService)
    {
        try {

            $article = $articleManagerService->getArticleFromRequest();

            $article->delete();

            $this->manager->flushData();

            $this->setData(true);

            return new ArticleDeleted($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleDeletionFail($this->getResponse());
    }
}