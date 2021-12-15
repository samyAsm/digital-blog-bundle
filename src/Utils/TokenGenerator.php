<?php
/**
 * Date: 24/08/20
 * Time: 16:10
 */

namespace DhiBlogBundle\Utils;


class TokenGenerator
{
    public static function getToken()
    {
        return RandomStringGenerator::generate(80, true);
    }
}