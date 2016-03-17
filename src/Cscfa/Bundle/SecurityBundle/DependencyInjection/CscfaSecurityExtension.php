<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
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

namespace Cscfa\Bundle\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Cscfa\Bundle\ToolboxBundle\Search\Directory\DirectorySearchTool;

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
class CscfaSecurityExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @param array            $configs   The extension configuration
     * @param ContainerBuilder $container The bundle ContainerBuilder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $baseDir = __DIR__.'/../Resources/config';

        $loader = new Loader\YamlFileLoader($container, new FileLocator($baseDir));
        $directorySearch = new DirectorySearchTool($baseDir);
        $parameters = $directorySearch->searchFilename('/parameters.yml/', true, $baseDir);
        $services = $directorySearch->searchFilename('/services.yml/', true, $baseDir);

        foreach ($parameters as $parameter) {
            $loader->load($parameter);
        }
        foreach ($services as $service) {
            $loader->load($service);
        }
    }
}
