<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * StrategistFormCompilerPass class.
 *
 * The StrategistFormCompilerPass
 * compile the service informations
 * for the strategist form factories.
 *
 * @category CompilerPass
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class StrategistFormCompilerPass implements CompilerPassInterface
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
        $bundleConfiguration = $container->getParameter('cscfa_csmanager_use_case');
        $configuration = $bundleConfiguration['strategy_factory'];

        $tagName = $configuration['tag_name'];
        $targetTag = $configuration['target_tag'];
        $method = $configuration['method'];

        $factories = $this->getFactories($container, $tagName);
        $mappedFactories = $this->mapTargets($factories, $targetTag);
        $this->injectTarget($container, $mappedFactories, $method);
    }

    /**
     * Get factories.
     *
     * This method return the
     * tagged factories
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $tagName   The factory tag name
     *
     * @return array:[ servicesIds ]
     */
    protected function getFactories(ContainerBuilder $container, $tagName)
    {
        return $container->findTaggedServiceIds($tagName);
    }

    /**
     * Map targets.
     *
     * This method map the target
     * with the target tag to find
     * and infect the strategies.
     *
     * @param array:[ serviceIds ] $factories     The factories
     * @param string               $targetTagName The target tag name
     *
     * @return array:[ array:[ serviceId => targetTagName ] ]
     */
    protected function mapTargets($factories, $targetTagName)
    {
        $map = array();
        foreach ($factories as $id => $tags) {
            $target = null;
            foreach ($tags as $attributes) {
                if (array_key_exists($targetTagName, $attributes)) {
                    $target = $attributes[$targetTagName];
                }
            }

            if ($target !== null) {
                $map[] = array($id => $target);
            }
        }

        return $map;
    }

    /**
     * Inject target.
     *
     * This method inject the target
     * services into the factories.
     *
     * @param ContainerBuilder                               $container       The container builder
     * @param array:[ array:[ serviceId => targetTagName ] ] $mappedFactories The map strategies's targets
     * @param string                                         $method          The injection method
     */
    protected function injectTarget(ContainerBuilder $container, $mappedFactories, $method)
    {
        foreach ($mappedFactories as $factoryMap) {
            foreach ($factoryMap as $factory => $target) {
                $taggedServices = $container->findTaggedServiceIds($target);
                $definition = $container->getDefinition($factory);

                foreach ($taggedServices as $id => $tags) {
                    foreach ($tags as $attributes) {
                        $definition->addMethodCall($method, array(new Reference($id), $attributes['alias']));
                    }
                }
            }
        }
    }
}
