<?php
/**
 * Date: 06/10/20
 * Time: 07:30
 */

namespace DhiBlogBundle\Services;


use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatorProviderService
{
    /**
     * @var TranslatorInterface
     */
    private $translator;


    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}