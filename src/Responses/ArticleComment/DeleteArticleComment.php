<?php
/**
 * Date: 07/09/21
 * Time: 15:41
 */

namespace Dhi\BlogBundle\Responses\ArticleComment;

use Dhi\BlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class DeleteArticleComment extends AbstractResponse
{
    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function web()
    {
        return $this->buildWebResponse(
            $this->getTemplate()
        );
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function getTemplate()
    {
        try{
            return $this->twig->render('digital-blog/comments/comment-delete-form.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/back/comments/comment-delete-form.html.twig', $this->buildViewParameters());
        }
    }
}