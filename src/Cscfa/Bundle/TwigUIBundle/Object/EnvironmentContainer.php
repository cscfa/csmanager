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
}
