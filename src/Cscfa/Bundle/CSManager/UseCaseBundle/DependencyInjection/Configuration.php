<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Bundle
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @category Bundle
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('cscfa_csmanager_use_case');

        $rootNode
            ->children()
                ->arrayNode('strategy_factory')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('tag_name')
                            ->defaultValue('cs.strategy_factory')
                        ->end()
                        ->scalarNode('target_tag')
                            ->defaultValue('cs.strategy_factory.target')
                        ->end()
                        ->scalarNode('method')
                            ->defaultValue('addStrategy')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
