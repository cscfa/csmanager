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

namespace Cscfa\Bundle\TwigUIBundle\Object;

use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Object\ControllerInformation\ControllerInfo;
use Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy\TwigHierarchy;

/**
 * EnvironmentContainer.
 *
 * The EnvironmentContainer is used to control a
 * controller environment. Also, store needed variables
 * and needed twig rendering.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentContainer
{
    /**
     * Objects container.
     *
     * This property store an ObjectsContainer instance
     * that allow a container to store severals objects
     * as process environment elements.
     *
     * @var ObjectsContainer
     */
    protected $objectsContainer;

    /**
     * Twig requests.
     *
     * This property store a TwigRequestIterator
     * instance. This offer support to store a
     * list of twig template to render.
     *
     * @var TwigRequestIterator
     */
    protected $twigRequests;

    /**
     * Controller info.
     *
     * This property store a ControllerInfo instance
     * to provide access on controller and method name
     * for the modules.
     *
     * @var ControllerInfo
     */
    protected $controllerInfo;

    /**
     * Twig hierachy.
     *
     * This property store a TwigHierarchy
     * to manage the TwigRequest registration
     * against the modules executions.
     *
     * @var TwigHierarchy
     */
    protected $twigHierarchy;

    /**
     * Set object container.
     *
     * This method register an object container
     * to store the objects variables.
     *
     * @param ObjectsContainer $container The ObjectContainer to register
     *
     * @return EnvironmentContainer
     */
    public function setObjectsContainer(ObjectsContainer $container)
    {
        $this->objectsContainer = $container;

        return $this;
    }

    /**
     * Get object container.
     *
     * This method return the register container if
     * defined, or null.
     *
     * @return ObjectsContainer
     */
    public function getObjectsContainer()
    {
        return $this->objectsContainer;
    }

    /**
     * Get twig requests.
     *
     * This method return a TwigRequestIterator instance
     * that store the twig templates to render.
     *
     * @return TwigRequestIterator
     */
    public function getTwigRequests()
    {
        return $this->twigRequests;
    }

    /**
     * Set twig requests.
     *
     * This method allow to store a TwigRequestIterator instance
     * to store a set of twig templates to render.
     *
     * @param TwigRequestIterator $twigRequests The TwigRequestIterator instance to use
     *
     * @return EnvironmentContainer
     */
    public function setTwigRequests(TwigRequestIterator $twigRequests)
    {
        $this->twigRequests = $twigRequests;

        return $this;
    }

    /**
     * Get controller info.
     *
     * This method return the current ControllerInfo instance
     * or null if no one is registered.
     *
     * @return ControllerInfo
     */
    public function getControllerInfo()
    {
        return $this->controllerInfo;
    }

    /**
     * Set controller info.
     *
     * This method allow to set the ControllerInfo
     * instance. It not update the instance if the
     * registered is currently immutable.
     *
     * @param ControllerInfo $controllerInfo The ControllerInfo to store
     *
     * @return EnvironmentContainer
     */
    public function setControllerInfo(ControllerInfo $controllerInfo)
    {
        if ($this->controllerInfo !== null && $this->controllerInfo->isImmutable()) {
            return $this;
        }

        $this->controllerInfo = $controllerInfo;

        return $this;
    }

    /**
     * Set twig hierarchy.
     *
     * This method allow to register
     * a TwigHierarchy to manage the
     * TwigRequest registration.
     *
     * @param TwigHierarchy $hierarchy The TwigHierarchy instance
     *
     * @return EnvironmentContainer
     */
    public function setTwigHierarchy(TwigHierarchy $hierarchy)
    {
        $this->twigHierarchy = $hierarchy;

        return $this;
    }

    /**
     * Get twig hierarchy.
     *
     * This method return the current
     * instance of TwigHierarchy.
     *
     * @return TwigHierarchy
     */
    public function getTwigHierarchy()
    {
        return $this->twigHierarchy;
    }
}
