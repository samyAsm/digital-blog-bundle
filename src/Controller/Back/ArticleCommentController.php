<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace Dhi\BlogBundle\Controller\Back;


use Dhi\BlogBundle\Annotations\AuthorMustAuthenticate;
use Dhi\BlogBundle\Core\Controller\CoreController;
use Dhi\BlogBundle\Responses\ArticleComment\ArticleCommentDeleted;
use Dhi\BlogBundle\Responses\ArticleComment\ArticleCommentDeletionFail;
use Dhi\BlogBundle\Responses\ArticleComment\ArticleCommentList;
use Dhi\BlogBundle\Services\Managers\ArticleCommentManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/article-comment", name="digital_blog_article_comment_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ Categories")
 *
 * @AuthorMustAuthenticate()
 */
class ArticleCommentController extends CoreController
{
    /**
     * @Route(path="/list", name="list", methods={"GET"})
     *
     * @SWG\Get(description="Get article_comments list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get article_comments list"
     * )
     *
     * @return Response
     */
    public function list()
    {
        try {

            $article_comments = $this->repositoryService->getArticleCommentRepository()
                ->getAllUnDeleted();

            $this->setData($article_comments, 'article_comments');

            return new ArticleCommentList($this->getResponse());

        } catch (\Throwable $e) {
            dd($e);
            $this->setMessage($e->getMessage());
        }

        return new ArticleCommentList($this->getResponse());
    }

    /**
     * @Route(path="/delete", name="delete", methods={"POST"})
     *
     * @SWG\Post(description="Delete article_comment")
     * @SWG\Parameter(name="article_comment_id", type="string", description="ArticleComment's id",
     *     in="query", default="ca4a1329")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleCommentManagerService $article_commentManagerService
     * @return Response
     */
    public function delete(ArticleCommentManagerService $article_commentManagerService)
    {
        try {

            $article_comment = $article_commentManagerService->getArticleCommentFromRequest();

            $article_comment->getArticle()
                ->removeComment($article_comment);

            $article_comment->delete();

            $this->manager->flushData();

            $this->setData(true);

            return new ArticleCommentDeleted($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleCommentDeletionFail($this->getResponse());
    }
}