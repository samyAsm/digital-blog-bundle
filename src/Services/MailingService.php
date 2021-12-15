<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 13/12/21
 * Time: 11:21
 */

namespace DhiBlogBundle\Services;


use DhiBlogBundle\Entity\Author;
use Swift_Message;
use Twig\Error\LoaderError;

class MailingService
{
    private $message;

    private $container;

    private $mailer;
    /**
     * @var TranslatorProviderService
     */
    private $translatorProviderService;

    private $twig;
    /**
     * @var EnvService
     */
    private $envService;

    public function __construct(KernelService $kernelService,
                                EnvService $envService,
                                TranslatorProviderService $translatorProviderService)
    {
        $this->message = new Swift_Message();

        $this->container = $kernelService->getContainer();

        $this->mailer = $this->container->get('swiftmailer.mailer.default');

        $this->twig = $this->container->get('twig');

        $this->translatorProviderService = $translatorProviderService;

        $this->envService = $envService;
    }

    /**
     * @param Author $author
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
     */
    public function sendResetPasswordCode(Author $author)
    {
        $this->message->setSubject($this->translatorProviderService->getTranslator()->trans(
            "Code de réinitialisation de mot de passe"
        ))
            ->setFrom(
                $this->envService->getParam('DIGITAL_BLOG_EMAIL_SENDER',
                    'example@example.com'),
                $this->envService->getParam('DIGITAL_BLOG_EMAIL_FROM',
                    'example'
                )
            )
            ->setTo($author->getEmail());

        try {
            $this->message->setBody($this->twig->render('digital-blog/emails/reset-password-code.html.twig', [
                'author' => $author
            ]));
        } catch (LoaderError $loaderError) {
            $this->message->setBody($this->twig->render('@DigitalBlog/emails/reset-password-code.html.twig', [
                'author' => $author
            ]));
        }

        $this->message->setContentType('text/html');

        return $this->mailer->send($this->message);
    }

    /**
     * @param Author $author
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \DhiBlogBundle\Exceptions\InvalidArgumentException
     */
    public function sendSettingPasswordCode(Author $author)
    {
        $this->message->setSubject($this->translatorProviderService->getTranslator()->trans(
            "Confirmez votre compte, vous êtes désormais un auteur sur notre blog."
        ))
            ->setFrom(
                $this->envService->getParam('DIGITAL_BLOG_EMAIL_SENDER',
                    'example@example.com'),
                $this->envService->getParam('DIGITAL_BLOG_EMAIL_FROM',
                    'example'
                )
            )
            ->setTo($author->getEmail());

        try {
            $this->message->setBody($this->twig->render('digital-blog/emails/password-setting-code.html.twig', [
                'author' => $author
            ]));
        } catch (LoaderError $loaderError) {
            $this->message->setBody($this->twig->render('@DigitalBlog/emails/password-setting-code.html.twig', [
                'author' => $author
            ]));
        }

        $this->message->setContentType('text/html');

        return $this->mailer->send($this->message);
    }

}