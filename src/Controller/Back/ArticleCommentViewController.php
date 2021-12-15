<?php
/**
 * Date: 03/09/21
 * Time: 16:56
 */

namespace DhiBlogBundle\Controller\Back;

use DhiBlogBundle\Annotations\MustAuthenticate;
use DhiBlogBundle\Core\Controller\CoreController;
use DhiBlogBundle\Responses\ArticleComment\ArticleCommentForm;
use DhiBlogBundle\Responses\ArticleComment\DeleteArticleComment;
use DhiBlogBundle\Services\Managers\ArticleCommentManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/article-comment", name="digital_blog__article_comment_")
 *
 * @SWG\Tag(name="Administration ▶ Blog ▶ ArticleComments")
 *
 * @MustAuthenticate()
 */
class ArticleCommentViewController extends CoreController
{
    /**
     *
     * @Route(path="/edit/{article_comment_id}", name="edit", methods={"GET"})
     *
     * @SWG\Post(description="Edit article_comment")
     *
     * @SWG\Parameter(name="article_comment_id", type="string", description="ArticleComment id",
     *     in="path", default="123456")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleCommentManagerService $article_commentManagerService
     * @return Response
     */
    public function edit(ArticleCommentManagerService $article_commentManagerService)
    {
        try {

            $article_comment = $article_commentManagerService->getArticleCommentFromRequest(false);

            if ($article_comment) $this->setData($article_comment, 'article_comment');

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleCommentForm($this->getResponse());
    }

    /**
     * @Route(path="/delete/{article_comment_id}", name="delete_form", methods={"GET"})
     *
     * @SWG\Post(description="Delete article_comment")
     * @SWG\Parameter(name="article_comment_id", type="string", description="ArticleComment id",
     *     in="path", default="123456")
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

            $this->setData($article_comment, 'article_comment');

            return new DeleteArticleComment($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return $this->badRequest();
    }
}