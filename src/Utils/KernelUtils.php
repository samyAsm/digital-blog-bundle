<?php
/**
 * Date: 02/09/21
 * Time: 10:17
 */

namespace DhiBlogBundle\Utils;


class KernelUtils
{
    public static function getKernel()
    {
        global $kernel;
        return $kernel;
    }
}