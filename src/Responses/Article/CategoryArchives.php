<?php
/**
 * Date: 07/09/21
 * Time: 15:41
 */

namespace DhiBlogBundle\Responses\Article;


use DhiBlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class CategoryArchives extends AbstractResponse
{
    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function getTemplate()
    {
        try{
            return $this->twig->render('digital-blog/category-archives.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/category-archives.html.twig', $this->buildViewParameters());
        }
    }
}