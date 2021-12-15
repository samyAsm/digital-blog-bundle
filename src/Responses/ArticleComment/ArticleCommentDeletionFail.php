<?php

namespace DhiBlogBundle\Responses\ArticleComment;


use DhiBlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class ArticleCommentDeletionFail extends AbstractResponse
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
            return $this->twig->render('digital-blog/comments/comment-deletion-fail.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/back/comments/comment-deletion-fail.html.twig', $this->buildViewParameters());
        }
    }
}