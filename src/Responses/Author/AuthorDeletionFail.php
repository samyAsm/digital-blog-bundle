<?php

namespace Dhi\BlogBundle\Responses\Author;


use Dhi\BlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class AuthorDeletionFail extends AbstractResponse
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
            return $this->twig->render('digital-blog/authors/author-deletion-fail.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/back/authors/author-deletion-fail.html.twig', $this->buildViewParameters());
        }
    }
}