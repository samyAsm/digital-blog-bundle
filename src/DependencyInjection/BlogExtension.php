<?php
/**
 * Date: 27/11/21
 * Time: 07:01
 */

namespace Dhi\BlogBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BlogExtension extends Extension
{
    protected const ALIAS = "digital_blog";

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
        $xml_loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $yaml_loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $xml_loader->load('services.xml');

        $container->setParameter('blog_configurations', $configs);
    }

    public function getAlias()
    {
        return self::ALIAS;
    }

}