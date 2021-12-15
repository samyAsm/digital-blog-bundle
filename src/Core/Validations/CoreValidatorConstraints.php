<?php
/**
 * Date: 16/04/21
 * Time: 12:13
 */

namespace DhiBlogBundle\Core\Validations;


use DhiBlogBundle\Service\EnvService;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class CoreValidatorConstraints extends ConstraintValidator
{
    /**
     * @var TranslatorInterface|null $translator
    */
    protected $translator;

    protected $repositoryService;

    /**
     * @var EnvService $envService
    */
    protected $envService;

    public function __construct()
    {
        /*I will use the global container, because, can't auto wire services*/

        global $kernel;
        $c = $kernel->getContainer();
        $this->translator = $c->get('app.translator_provider_service')->getTranslator();
        $this->repositoryService = $c->get('app.repository_service');
        $this->envService = $c->get('app.env_service');

    }
}