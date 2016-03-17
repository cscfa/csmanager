<?php

namespace Cscfa\Bundle\CSManager\RssApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cscfa_cs_manager_rss_api');

        $rootNode
            ->children()
                ->scalarNode('basePath')->defaultNull()->end()
            ->end()
            ->children()
                ->scalarNode('webmaster')->defaultNull()->end()
            ->end()
            ->children()
                ->scalarNode('author')->defaultNull()->end()
            ->end()
            ->children()
            ->arrayNode('image')
                ->children()
                    ->scalarNode('path')->defaultNull()->end()
                    ->scalarNode('width')->defaultNull()->end()
                    ->scalarNode('height')->defaultNull()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
