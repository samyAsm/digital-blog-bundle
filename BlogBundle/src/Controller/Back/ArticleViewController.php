<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;


use Dhi\BlogBundle\Annotations\MustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Entity\Article;
use Dhi\BlogBundle\Responses\Article\ArticleDeletionFail;
use Dhi\BlogBundle\Responses\Article\ArticleForm;
use Dhi\BlogBundle\Responses\Article\DeleteArticle;
use Dhi\BlogBundle\Services\Managers\ArticleManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/article", name="digital_blog_article_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Articles")
 *
 * @MustAuthenticate()
 */
class ArticleViewController extends CoreController
{
    /**
     * @Route(path="/edit/{article_id}", name="edit", methods={"GET"})
     *
     * @SWG\Post(description="Edit article")
     *
     * @SWG\Parameter(name="article_id", type="string", description="Article id",
     *     in="path", default="123456")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleManagerService $articleManagerService
     * @return Response
     */
    public function edit(ArticleManagerService $articleManagerService)
    {
        try {

            $article = $articleManagerService->getArticleFromRequest(false);

            $categories = $this->repositoryService->getCategoryRepository()->getAllUnDeleted();

            $statuses = Article::STATUTES;

            $this->setData($categories, 'categories');

            $this->setData($statuses, 'statuses');

            if ($article){

                $article->buildPropertiesFromContent();

                $this->setData($article, 'article');
            }

            return new ArticleForm($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleForm($this->getResponse());
    }

    /**
     * @Route(path="/delete/{article_id}", name="delete_form", methods={"GET"})
     *
     * @SWG\Post(description="Delete article")
     * @SWG\Parameter(name="article_id", type="string", description="Article id",
     *     in="path", default="123456")
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

            $this->setData($article, 'article');

            return new DeleteArticle($this->getResponse());

        } catch (\Throwable $e) {
            dd($e);
            $this->setMessage($e->getMessage());
        }

        return new ArticleDeletionFail($this->getResponse());
    }
}