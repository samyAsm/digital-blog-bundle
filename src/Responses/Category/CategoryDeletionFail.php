<?php

namespace DhiBlogBundle\Responses\Category;


use DhiBlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class CategoryDeletionFail extends AbstractResponse
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
            return $this->twig->render('digital-blog/categories/category-deletion-fail.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/back/categories/category-deletion-fail.html.twig', $this->buildViewParameters());
        }
    }
}