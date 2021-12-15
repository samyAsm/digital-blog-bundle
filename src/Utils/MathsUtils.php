<?php
/**
 * Date: 06/12/20
 * Time: 08:51
 */

namespace DhiBlogBundle\Utils;


trait MathsUtils
{
    public static function convertXafToEur(?float $value)
    {
        $c = floatval($_ENV['EUR_TO_XAF']);

        return $c?floatval($value)/$c: 0;
    }
}