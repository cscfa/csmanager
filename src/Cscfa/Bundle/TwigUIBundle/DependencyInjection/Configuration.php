<?php
/**
 * This file is a part of CSCFA TwigUI project.
 *
 * The TwigUI project is a rendering bundle written in php
 * with Symfony2 framework.
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

namespace Cscfa\Bundle\TwigUIBundle\DependencyInjection;

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

        $rootNode = $treeBuilder->root('cscfa_twig_ui');

        $rootNode
            ->children()
                ->arrayNode('modules')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('tag_name')
                            ->defaultValue('cs.module')
                        ->end()
                        ->scalarNode('parent_tag')
                            ->defaultValue('cs.module.parent')
                        ->end()
                        ->scalarNode('method_tag')
                            ->defaultValue('cs.module.hydrate')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
