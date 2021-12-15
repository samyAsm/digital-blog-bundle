<?php

namespace Dhi\BlogBundle;


use Dhi\BlogBundle\DependencyInjection\BlogExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DigitalBlogBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function getContainerExtension()
    {
        return new BlogExtension();
    }
}