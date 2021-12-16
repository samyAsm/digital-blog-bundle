<?php
/**
 * Date: 24/08/20
 * Time: 16:10
 */

namespace Dhi\BlogBundle\Utils;


class TokenGenerator
{
    public static function getToken()
    {
        return RandomStringGenerator::generate(80, true);
    }
}