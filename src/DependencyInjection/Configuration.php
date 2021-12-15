<?php
/**
 * Date: 27/11/21
 * Time: 08:01
 */

namespace DhiBlogBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    protected const ALIAS = "digital_blog";

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ALIAS);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('routing')
                    ->children()
                        ->scalarNode('prefix')->end()
                    ->end()
                ->end()//routing

                ->arrayNode('assets')
                    ->children()
                        ->scalarNode('logo')->end()
                        ->scalarNode('hero_bg')->end()
                    ->end()
                ->end()//assets

                ->arrayNode('theme')
                    ->children()
                        ->scalarNode('color_primary')->end()
                        ->scalarNode('color_secondary')->end()
                    ->end()
                ->end()//theme

                ->arrayNode('store')
                    ->children()
                        ->scalarNode('author_image_store')->end()
                        ->scalarNode('article_image_store')->end()
                        ->scalarNode('category_image_store')->end()
                    ->end()
                ->end()//store
            ->end();

        return $treeBuilder;
    }
}