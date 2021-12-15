<?php
/**
 * Date: 22/09/21
 * Time: 18:26
 */

namespace Dhi\BlogBundle\Services;

use Dhi\BlogBundle\Core\Entity\CoreEntity;
use Dhi\BlogBundle\Core\Exceptions\Alert;
use Dhi\BlogBundle\Validator\EntityValidator;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

class ValidatorService
{

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var KernelService
     */
    private $kernelService;

    private $serviceProvider;

    /**
     * ValidatorService constructor.
     * @param TranslatorProviderService $translatorProviderService
     * @param KernelService $kernelService
     */
    public function __construct(TranslatorProviderService $translatorProviderService,
                                KernelService $kernelService)
    {
        $this->translator = $translatorProviderService->getTranslator();
        $this->kernelService = $kernelService;
        $this->serviceProvider = $kernelService->getContainer();
    }

    /**
     * @param CoreEntity $data
     * @return ValidatorService
     * @throws Alert
     */
    public function validate(CoreEntity $data)
    {
        $factory = new EntityValidator();

        $factory->addValidator('doctrine.orm.validator.unique',
            $this->serviceProvider->get('app.unique_entity_validator'));

        $builder = Validation::createValidatorBuilder();
        $builder->setConstraintValidatorFactory($factory);
        $builder->enableAnnotationMapping();

        $validator = $builder->getValidator();

        $violations = $validator->validate($data);

        foreach ($violations as $key => $violation) {
            /**
             * @var ConstraintViolation|null $violation
             */
            throw new Alert($this->translator->trans($violation->getMessage()));
        }

        return $this;

    }
}