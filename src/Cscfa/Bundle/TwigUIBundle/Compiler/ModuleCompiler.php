<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category CompilerPass
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ModuleCompiler class.
 *
 * The ModuleCompiler compile the service
 * informations for the modules.
 *
 * @category CompilerPass
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ModuleCompiler implements CompilerPassInterface
{
    /**
     * Process.
     *
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container The container builder
     *
     * @see CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        $bundleConfiguration = $container->getParameter('cscfa_twig_ui');
        $configuration = $bundleConfiguration['modules'];

        $tagName = $configuration['tag_name'];
        $parentTag = $configuration['parent_tag'];
        $method = $configuration['method_tag'];

        $modules = $this->getModules($container, $tagName);
        foreach ($modules as $module => $tags) {
            $this->addParent($container, $parentTag, $method, $module, $tags);
        }
    }

    /**
     * Get modules.
     *
     * This method return the
     * tagged modules
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $tagName   The module tag name
     *
     * @return array:[ servicesIds ]
     */
    protected function getModules(ContainerBuilder $container, $tagName)
    {
        return $container->findTaggedServiceIds($tagName);
    }

    /**
     * Add parent.
     *
     * This method register a module
     * into it's defined parent.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $parentTag The parent tag name
     * @param string           $method    The method name
     * @param mixed            $module    The moduel service
     * @param array            $tags      The tags of the module
     */
    protected function addParent(ContainerBuilder $container, $parentTag, $method, $module, $tags)
    {
        foreach ($tags as $tag) {
            $definition = $container->getDefinition($tag[$parentTag]);
            $definition->addMethodCall($tag[$method], array(new Reference($module)));
        }
    }
}
