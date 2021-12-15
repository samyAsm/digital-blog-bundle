<?php

namespace DhiBlogBundle\Responses\Article;

use DhiBlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class ArticleDeletionFail extends AbstractResponse
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
            return $this->twig->render('digital-blog/articles/article-deletion.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/back/articles/article-deletion-fail.html.twig', $this->buildViewParameters());
        }
    }
}