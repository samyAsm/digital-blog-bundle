<?php
/**
 * Date: 14/04/21
 * Time: 11:01
 */

namespace Dhi\BlogBundle\Services\Managers;


use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Entity\Article;
use Dhi\BlogBundle\Entity\ArticleComment;
use Dhi\BlogBundle\Services\RepositoryService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticleCommentManagerService
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
     * PaymentManagerService constructor.
     * @param RequestStack $requestStack
     * @param RepositoryService $repositoryService
     * @param TranslatorInterface $translator
     */
    public function __construct(RequestStack $requestStack,
                                RepositoryService $repositoryService,
                                TranslatorInterface $translator)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repositoryService = $repositoryService;
        $this->translator = $translator;
    }

    /**
     * @param Article $article
     * @param ArticleComment|null $article_comment
     * @return ArticleComment|null
     * @throws \Exception
     */
    public function buildArticleCommentFromRequest(Article $article, ?ArticleComment $article_comment = null)
    {
        if (!$article_comment) $article_comment = new ArticleComment();

        $author_name = $this->request->get('author_name');
        $author_email = $this->request->get('author_email');
        $comment = $this->request->get('comment');

        $article_comment
            ->setAuthorName($author_name)
            ->setAuthorEmail($author_email)
            ->setComment($comment)
            ->setArticle($article)
        ;

        return $article_comment;
    }

    /**
     * @param bool|null $strict
     * @return ArticleComment|null
     * @throws Alert
     */
    public function getArticleCommentFromRequest(?bool $strict = true): ?ArticleComment
    {

        $article_comment_id = $this->request->get('article_comment_id');

        /**
         * @var ArticleComment|null $article_comment
         */
        $article_comment = $this->repositoryService->getArticleCommentRepository()
            ->findByUniqKeyUnDeleted($article_comment_id);

        if ($strict && !$article_comment)
            throw new Alert(
                $this->translator->trans("Veuillez fournir une catégorie valide")
            );

        return $article_comment;
    }
}