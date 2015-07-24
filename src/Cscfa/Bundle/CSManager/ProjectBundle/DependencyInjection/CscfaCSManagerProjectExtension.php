<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * @license http://opensource.org/licenses/MIT MIT
 * @author Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @filesource
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * @category Symfony Bundle configuration
 * @package CSManager
 * @author Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license http://opensource.org/licenses/MIT MIT
 * @link http://cscfa.fr
 */
class CscfaCSManagerProjectExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
