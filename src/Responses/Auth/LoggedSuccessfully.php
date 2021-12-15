<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 13/12/21
 * Time: 16:09
 */

namespace DhiBlogBundle\Responses\Auth;


use DhiBlogBundle\Core\Response\AbstractResponse;
use Twig\Error\LoaderError;

class LoggedSuccessfully extends AbstractResponse
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
            return $this->twig->render('digital-blog/auth/logged-successfully.html.twig', $this->buildViewParameters());
        }catch (LoaderError $loaderError){
            return $this->twig->render('@DigitalBlog/auth/logged-successfully.html.twig', $this->buildViewParameters());
        }
    }
}