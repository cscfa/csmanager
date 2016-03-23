<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Builders;

/**
 * EnvironmentOptionBuilder.
 *
 * The EnvironmentOptionBuilder is used
 * to build the EnvironmentFactory options.
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentOptionBuilder
{
    /**
     * OBJECT_CONTAINER_OBJECT.
     *
     * This property target the
     * add object to ObjectContainer
     * option.
     *
     * @var string
     */
    const OBJECT_CONTAINER_OBJECT = 'objectContainer_object';

    /**
     * CONTROLLER_INFO_CONTROLLER.
     *
     * This constant target the
     * set controller name to
     * ControllerInfo option.
     *
     * @var string
     */
    const CONTROLLER_INFO_CONTROLLER = 'controllerInfo_controller';

    /**
     * CONTROLLER_INFO_METHOD.
     *
     * This constant target the
     * set method name to
     * ControllerInfo option.
     *
     * @var string
     */
    const CONTROLLER_INFO_METHOD = 'controllerInfo_method';

    /**
     * TWIG_REQUEST_TWIG_REQUEST.
     *
     * This constant target the
     * add TwigRequest to
     * TwigRequest option.
     *
     * @var string
     */
    const TWIG_REQUEST_TWIG_REQUEST = 'twigRequest_twigRequest';

    /**
     * Object container.
     *
     * This property store the
     * ObjectContainer options.
     *
     * @var array
     */
    protected $objectContainer = array();

    /**
     * Controller info.
     *
     * This property store the
     * ControllerInfo options.
     *
     * @var array
     */
    protected $controllerInfo = array();

    /**
     * Twig requests.
     *
     * This property store the
     * TwigRequests options.
     *
     * @var array
     */
    protected $twigRequests = array();

    /**
     * Get option.
     *
     * This method return the
     * options to pass to the
     * EnvironmentFactory.
     *
     * @return array
     */
    public function getOption()
    {
        return array(
            'ObjectContainer' => $this->objectContainer,
            'ControllerInfo' => $this->controllerInfo,
            'TwigRequests' => $this->twigRequests,
        );
    }

    /**
     * Add option.
     *
     * This method add an option
     * to the stored properties.
     *
     * @param string $type      The option type as EnvironmentOptionBuilder constant
     * @param mixed  $arguments The argument of the option
     *
     * @return EnvironmentOptionBuilder
     */
    public function addOption($type, $arguments)
    {
        switch ($type) {
            case self::OBJECT_CONTAINER_OBJECT:
                $this->objectContainer[] = array(
                    'object' => $arguments,
                );

                return $this;
            case self::CONTROLLER_INFO_CONTROLLER:
                $this->controllerInfo[] = array(
                    'controllerName' => $arguments,
                );

                return $this;
            case self::CONTROLLER_INFO_METHOD:
                $this->controllerInfo[] = array(
                    'methodName' => $arguments,
                );

                return $this;
            case self::TWIG_REQUEST_TWIG_REQUEST:
                $this->twigRequests[] = array(
                    'twigRequest' => $arguments,
                );

                return $this;
        }
    }
}
