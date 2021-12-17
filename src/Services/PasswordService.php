<?php

namespace Dhi\BlogBundle\Services;

use Dhi\BlogBundle\Core\Entity\AbstractUser;
use Dhi\BlogBundle\Core\Exceptions\Alert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EnvService
     */
    private $env;

    private $translator;

    public function __construct(UserPasswordEncoderInterface $encoder,
                                EnvService $env,
                                TranslatorProviderService $translatorProviderService)
    {
        $this->encoder = $encoder;
        $this->env = $env;
        $this->translator = $translatorProviderService->getTranslator();
    }

    public final function isPasswordValid(AbstractUser $user, ?string $password)
    {
        return $this->encoder->isPasswordValid($user, $password);
    }

    public function encodePassword(UserInterface $user, ?string $password)
    {
        return $this->encoder->encodePassword($user, $password);
    }

    /**
     * @param $password
     * @throws Alert
     */
    public function checkPasswordPower($password)
    {
        try{
            $min_password  = $this->env->getParam("MIN_PASSWORD_SIZE", 6);
        }catch (\Throwable $t){
            throw new Alert($t->getMessage());
        }

        if (strlen($password) < $min_password)
            throw new Alert($this->translator
                ->trans("Votre mot de passe doit avoir au moins $min_password caractères"));
    }
}
