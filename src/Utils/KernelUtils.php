<?php
/**
 * Date: 02/09/21
 * Time: 10:17
 */

namespace Dhi\BlogBundle\Utils;


class KernelUtils
{
    public static function getKernel()
    {
        global $kernel;
        return $kernel;
    }
}