<?php

namespace DhiBlogBundle\Controller\Front;

use DhiBlogBundle\Core\Controller\AbstractRESTController;
use DhiBlogBundle\Responses\Article\Archives;
use DhiBlogBundle\Responses\Article\CategoryArchives;
use DhiBlogBundle\Responses\Article\SingleArticle;
use DhiBlogBundle\Responses\ArticleComment\ArticleCommentStored;
use DhiBlogBundle\Responses\ArticleComment\ArticleCommentStoreFail;
use DhiBlogBundle\Services\Managers\ArticleCommentManagerService;
use DhiBlogBundle\Services\Managers\ArticleManagerService;
use DhiBlogBundle\Services\Managers\CategoryManagerService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(path="/" ,name="digital_blog_")
*/
class BlogController extends AbstractRESTController
{
    /**
     * @Route(path="/", name="home", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns boolean",
     * )
     *
     * @return Response
     *
     */
    public function index()
    {
        try {

            $articles = $this->repositoryService->getArticleRepository()->getLatest();

            list($articles, $page, $current, $total, $limit, $next_page) = $this->paginator->paginateWithInterval($articles);

            $this->setData($articles, 'articles');
            $this->setData($page, 'page');
            $this->setData($current, 'current');
            $this->setData($total, 'total');
            $this->setData($limit, 'limit');
            $this->setData($next_page, 'next_page');

            return new Archives($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new Archives($this->getResponse());
    }

    /**
     * @Route(path="/category/{category_name}/{category_id}", name="article_by_category", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns boolean",
     * )
     *
     * @param CategoryManagerService $categoryManagerService
     * @return Response
     */
    public function articleByCategory(CategoryManagerService $categoryManagerService)
    {
        try {

            $category = $categoryManagerService->getCategoryFromRequest();

            $articles = $this->repositoryService->getArticleRepository()->getAllByCategory($category);

            foreach ($category->getChildCategories() as $index => $childCategory) {
                $articles = array_merge($articles, $this->repositoryService->getArticleRepository()->getAllByCategory($childCategory));
            }

            foreach ($articles as $index => $article) {
                $articles[$article->getUniqKey()] = $article;
                unset($articles[$index]);
            }

            list($articles, $page, $current, $total, $limit, $next_page) = $this->paginator->paginateWithInterval($articles);

            $this->setData($category, 'category');
            $this->setData($articles, 'articles');
            $this->setData($page, 'page');
            $this->setData($current, 'current');
            $this->setData($total, 'total');
            $this->setData($limit, 'limit');
            $this->setData($next_page, 'next_page');

            return new CategoryArchives($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return $this->redirectToRoute('digital_blog_home');
    }

    /**
     * @Route(path="/{article_id}/{article_slug}", name="article", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns boolean",
     * )
     *
     * @param ArticleManagerService $articleManagerService
     * @return Response
     */
    public function article(ArticleManagerService $articleManagerService)
    {
        try {

            $article = $articleManagerService->getArticleFromRequest();

            $this->setData($article, 'article');


            return new SingleArticle($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return $this->redirectToRoute('digital_blog_home');
    }

    /**
     *
     * @Route(path="/comment/store", name="comment_store", methods={"POST"})
     *
     * @SWG\Post(description="Create new article_comment")
     *
     * @SWG\Parameter(name="article_comment_id", type="string", description="ArticleComment's id",
     *     in="query", default="ca4a1329")
     * @SWG\Parameter(name="article_id", type="string", description="Article's id",
     *     in="query", default="ca4a1329")
     * @SWG\Parameter(name="name", type="string", description="ArticleComment's name",
     *     in="query", default="Reource")
     * @SWG\Parameter(name="source", type="file", description="ArticleComment file",
     *     in="formData", default="")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @param ArticleCommentManagerService $article_commentManagerService
     * @param ArticleManagerService $articleManagerService
     * @return Response
     */
    public function store(ArticleCommentManagerService $article_commentManagerService,
                          ArticleManagerService $articleManagerService)
    {
        try {

            $article_comment = $article_commentManagerService->buildArticleCommentFromRequest(
                $articleManagerService->getArticleFromRequest(),
                $article_commentManagerService->getArticleCommentFromRequest(false)
            );

            $this->validate($article_comment);

            $article_comment->touch();

            $this->manager->flushData($article_comment);

            $this->setData($article_comment, 'article_comment');

            return new ArticleCommentStored($this->getResponse());

        } catch (\Throwable $e) {
            $this->setMessage($e->getMessage());
        }

        return new ArticleCommentStoreFail($this->getResponse());
    }
}