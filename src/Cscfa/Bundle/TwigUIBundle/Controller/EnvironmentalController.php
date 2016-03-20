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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\TwigUIBundle\Factories\EnvironmentFactory;

/**
 * EnvironmentalController.
 *
 * The EnvironmentalController is define
 * base process to create environment
 * container.
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class EnvironmentalController extends Controller
{
    /**
     * Get environment.
     *
     * This method return an instance of
     * EnvironmentContainer with the
     * ControllerInfo register the controller
     * name and the method name. The
     * ObjectsContainer register the current
     * user and the current request.
     *
     * @param string $methodName The method name
     * @param array  $options    The factory options
     *
     * @return EnvironmentContainer
     */
    public function getEnvironment($methodName, array $options = array())
    {
        $factory = $this->get('EnvironmentContainerFactory');

        if ($factory instanceof EnvironmentFactory) {
            $currentClassName = substr(get_class($this), strrpos(get_class($this), '\\'));

            $factoryOption = array(
                'ObjectContainer' => array(
                    ['object' => array($this->getUser(), 'user')],
                    ['object' => array($this->getRequest(), 'request')],
                ),
                'ControllerInfo' => array(
                    ['controllerName' => $currentClassName],
                    ['methodName' => $methodName],
                ),
            );

            return $factory->getInstance(array_merge($factoryOption, $options));
        }
    }
}
