<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 10/12/21
 * Time: 11:13
 */

namespace DhiBlogBundle\Controller\Front;


use DhiBlogBundle\Core\Controller\CoreController;
use DhiBlogBundle\Core\Exceptions\Alert;
use DhiBlogBundle\Entity\Author;
use DhiBlogBundle\Responses\Auth\LoggedOutSuccessfully;
use DhiBlogBundle\Responses\Auth\LoggedSuccessfully;
use DhiBlogBundle\Responses\Auth\Login;
use DhiBlogBundle\Responses\Auth\LoginFail;
use DhiBlogBundle\Responses\Auth\ResetPasswordConfirm;
use DhiBlogBundle\Responses\Auth\ResetPasswordCodeFail;
use DhiBlogBundle\Responses\Auth\ResetPasswordCodeSentSuccessfully;
use DhiBlogBundle\Responses\Auth\ResetPasswordDoneSuccessfully;
use DhiBlogBundle\Responses\Auth\ResetPasswordFail;
use DhiBlogBundle\Responses\Auth\ResetPasswordRequestForm;
use DhiBlogBundle\Services\AuthorAuthenticatorService;
use DhiBlogBundle\Services\MailingService;
use DhiBlogBundle\Utils\RandomUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route(path="/", name="digital_blog_")
 */
class AuthController extends CoreController
{

    use RandomUtils;

    /**
     * @Route(path="/login", name="login_view", methods={"GET"})
     *
     * @throws \Exception
     */
    public function login_view()
    {
        return new Login($this->getResponse());
    }

    /**
     * @Route(path="/login", name="login", methods={"POST"})
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param AuthorAuthenticatorService $authenticatorService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(UserPasswordEncoderInterface $encoder,
                          AuthorAuthenticatorService $authenticatorService)
    {
        try{
            $credential = $this->request->get('credential');

            $password = $this->request->get('password');

            /**
             * @var Author|null $author
             */
            $author = $this->repositoryService->getAuthorRepository()
                ->findByCredentials($credential);

            if (!$author)
                throw new Alert("Utilisateur introuvable");

            if (!$encoder->isPasswordValid($author, $password))
                throw new Alert("Mot de passe incorrect");

            $authenticatorService->openSession($author);

            $this->manager->flushData($author);

            return new LoggedSuccessfully($this->getResponse());

        }catch (Alert $e){
            $this->setMessage($e->getMessage());
        }catch (\Throwable $throwable){
            $this->setMessage($throwable->getMessage());
        }

        return new LoginFail($this->getResponse());
    }

    /**
     * @Route(path="/logout", name="logout", methods={"GET"})
     *
     * @param AuthorAuthenticatorService $authenticatorService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(AuthorAuthenticatorService $authenticatorService)
    {
        try{

            $authenticatorService->closeSession();

            $this->manager->flushData();

        }catch (\Throwable $throwable){
            $this->setMessage($throwable->getMessage());
        }

        return new LoggedOutSuccessfully($this->getResponse());
    }

    /**
     * @Route(path="/reset-password-request", name="reset_password_request", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset_password_request()
    {
        return new ResetPasswordRequestForm($this->getResponse());
    }

    /**
     * @Route(path="/reset-password-confirm/{token}", name="reset_password_confirm_view", methods={"GET"})
     *
     * @param null $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset_password_confirm_view($token = null)
    {

        try{
            /**
             * @var Author|null $author
             */
            $author = $this->repositoryService->getAuthorRepository()
                ->findByResetPasswordToken($token);

            if (!$author)
                throw new Alert("Utilisateur introuvable");

            $this->setData($author->getResetPasswordToken(), 'token');

        }catch (\Throwable $throwable){
            $this->setMessage($throwable->getMessage());
        }

        return new ResetPasswordConfirm($this->getResponse());
    }

    /**
     * @Route(path="/request-reset-password", name="request_reset_password", methods={"POST"})
     *
     * @param MailingService $mailingService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function request_reset_password(MailingService $mailingService)
    {
        try{

            $email = $this->request->get('email');

            /**
             * @var Author|null $author
             */
            $author = $this->repositoryService->getAuthorRepository()
                ->findByCredentials($email);

            if (!$author)
                throw new Alert("Utilisateur introuvable");

            $author->setResetPasswordCode($this->getRandomCode(4));

            $author->setResetPasswordToken($this->getRandomUrlToken().$this->timestamp());

            $mailingService->sendResetPasswordCode($author);

            $this->setData($author->getResetPasswordToken(), 'token');

            $this->manager->flushData();

            return new ResetPasswordCodeSentSuccessfully($this->getResponse());

        }catch (Alert $e){
            $this->setMessage($e->getMessage());
        }catch (\Throwable $throwable){
            $this->setMessage($throwable->getMessage());
        }

        return new ResetPasswordCodeFail($this->getResponse());
    }

    /**
     * @Route(path="/reset-password", name="reset_password", methods={"POST"})
     *
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset_password(UserPasswordEncoderInterface $encoder)
    {
        try{

            $token = $this->request->get('token');

            $code = $this->request->get('code');

            $password = $this->request->get('password');

            $password_retype = $this->request->get('password_retype');

            /**
             * @var Author|null $author
             */
            $author = $this->repositoryService->getAuthorRepository()
                ->findByResetPasswordToken($token);

            if (!$author)
                throw new Alert("Utilisateur introuvable");

            if (!$code || $code != $author->getResetPasswordCode())
                throw new Alert("Code invalide");

            if ($password !== $password_retype)
                throw new Alert("Veuillez ressaisir correctement le mot de passe");

            $author->setPassword($encoder->encodePassword($author, $password));

            $author->setResetPasswordToken(null)
                ->setResetPasswordCode(null);

            $this->manager->flushData();

            return new ResetPasswordDoneSuccessfully($this->getResponse());

        }catch (Alert $e){
            $this->setMessage($e->getMessage());
        }catch (\Throwable $throwable){
            $this->setMessage($throwable->getMessage());
        }

        return new ResetPasswordFail($this->getResponse());
    }
}