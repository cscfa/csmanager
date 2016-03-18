<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Object\ControllerInformation;

use Cscfa\Bundle\TwigUIBundle\Interfaces\ImmutableInterface;

/**
 * ControllerInfo.
 *
 * The ControllerInfo is used to offer access for
 * controller name and current method to the modules.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ControllerInfo implements ImmutableInterface
{
    /**
     * Controller name.
     *
     * This property store the controller
     * name.
     *
     * @var string
     */
    protected $controllerName;

    /**
     * Method name.
     *
     * This property store the controller
     * method name.
     *
     * @var string
     */
    protected $methodName;

    /**
     * Immutable.
     *
     * This property define the ControllerInfo
     * immutable state.
     *
     * @var bool
     */
    protected $immutable;

    /**
     * Construct.
     *
     * The default ControllerInfo constructor.
     *
     * @param string $controllerName The controller name
     * @param string $methodName     The method name
     * @param string $immutable      The immutable state
     */
    public function __construct($controllerName = '', $methodName = '', $immutable = false)
    {
        $this->controllerName = $controllerName;
        $this->methodName = $methodName;
        $this->immutable = $immutable;
    }

    /**
     * Get controller.
     *
     * This method return the current controller name.
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Set controller name.
     *
     * This method allow to set the controller
     * name into the informations.
     *
     * @param string $controllerName The controller name
     *
     * @throws \Exception If the given controller name is not a string
     *
     * @return ControllerInfo
     */
    public function setControllerName($controllerName)
    {
        if (!$this->immutable) {
            if (!is_string($controllerName)) {
                throw new \Exception(
                    'Controller name must be a string',
                    500
                );
            }
            $this->controllerName = $controllerName;
        }

        return $this;
    }

    /**
     * Get method.
     *
     * This method return the current method name.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Set method name.
     *
     * This method allow to set the controller
     * method name into the informations.
     *
     * @param string $methodName The method name
     *
     * @throws \Exception If the given method name is not a string
     *
     * @return ControllerInfo
     */
    public function setMethodName($methodName)
    {
        if (!$this->immutable) {
            if (!is_string($methodName)) {
                throw new \Exception(
                    'Method name must be a string',
                    500
                );
            }
            $this->methodName = $methodName;
        }

        return $this;
    }

    /**
     * Is immutable.
     *
     * This method return true if the current instance
     * is in immutable state.
     *
     * @return bool
     */
    public function isImmutable()
    {
        return $this->immutable;
    }

    /**
     * Set immutable.
     *
     * This method allow to set the current
     * instance into an immutable state.
     *
     * @param bool $immutable The immutable state
     *
     * @return ImmutableInterface
     */
    public function setImmutable($immutable)
    {
        if (!$this->immutable) {
            $this->immutable = $immutable;
        }

        return $this;
    }
}
