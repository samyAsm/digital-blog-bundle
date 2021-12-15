<?php
/**
 * Date: 24/03/21
 * Time: 16:02
 */

namespace DhiBlogBundle\Core\Data;


use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableMessage implements TranslatableInterface
{

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans($locale);
    }
}