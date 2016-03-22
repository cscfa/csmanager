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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @category Bundle
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CscfaTwigUIExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @param array            $configs   The extension configuration
     * @param ContainerBuilder $container The bundle ContainerBuilder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $env = $container->getParameter('kernel.environment');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('ObjectsContainerBuilder.yml');
        $loader->load('ControllerInfoBuilder.yml');
        $loader->load('TwigRequestIteratorBuilder.yml');
        $loader->load('EnvironmentContainerFactory.yml');
        if ('test' == $env) {
            $testLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../FunctionalTest/config'));
            $testLoader->load('services.yml');
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('cscfa_twig_ui', $config);
    }
}
