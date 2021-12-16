<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 13/12/21
 * Time: 16:09
 */

namespace Dhi\BlogBundle\Responses\Auth;


use Dhi\BlogBundle\Core\Response\AbstractBadRequestResponse;
use Twig\Error\LoaderError;

class RedirectToNotAuthorizedResponse extends AbstractBadRequestResponse
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
        try {
            return $this->twig->render('digital-blog/auth/not-authorized.html.twig', $this->buildViewParameters());
        } catch (LoaderError $loaderError) {
            return $this->twig->render('@DigitalBlog/auth/not-authorized.html.twig', $this->buildViewParameters());
        }
    }
}