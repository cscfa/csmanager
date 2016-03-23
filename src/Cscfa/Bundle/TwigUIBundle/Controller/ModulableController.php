<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Controller;

use Cscfa\Bundle\TwigUIBundle\Builders\EnvironmentOptionBuilder;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;
use Symfony\Component\BrowserKit\Response;

/**
 * ModulableContainer.
 *
 * The ModulableContainer is define
 * base process to rrturn a module
 * result.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ModulableController extends EnvironmentalController
{
    /**
     * Process module.
     *
     * This method build the environment
     * container and process the module.
     *
     * @param string                   $template      The main action template
     * @param string                   $method        The caller method
     * @param string                   $module        The module service id
     * @param EnvironmentOptionBuilder $optionBuilder the environment factory option
     *
     * @throws \Exception If the module service is not an instance of ModuleSetInterface
     *
     * @return Response
     */
    public function processModule($template, $method, $module, EnvironmentOptionBuilder $optionBuilder = null)
    {
        $environment = null;
        if ($optionBuilder !== null) {
            $environment = $this->getEnvironment($method, $optionBuilder->getOption());
        } else {
            $environment = $this->getEnvironment($method);
        }

        $moduleService = $this->get($module);
        if ($moduleService instanceof ModuleSetInterface) {
            $moduleService->processAll($environment);

            return $this->render($template, array('environment' => $environment));
        } else {
            throw new \Exception('The module must be an instance of ModuleSetInterface', 500);
        }
    }
}
