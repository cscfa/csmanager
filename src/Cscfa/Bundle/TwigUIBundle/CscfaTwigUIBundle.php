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

namespace Cscfa\Bundle\TwigUIBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cscfa\Bundle\TwigUIBundle\Compiler\ModuleCompiler;

/**
 * CscfaTwigUIBundle class.
 *
 * The CscfaTwigUIBundle class define
 * the cor bundle.
 *
 * @category Bundle
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CscfaTwigUIBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ModuleCompiler());
    }
}
