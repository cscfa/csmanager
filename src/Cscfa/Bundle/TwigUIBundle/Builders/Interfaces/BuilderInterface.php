<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Builders\Interfaces;

/**
 * BuilderInterface.
 *
 * The BuilderInterface is used to define the
 * builder methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface BuilderInterface
{
    /**
     * Set element.
     *
     * This method allow the builder
     * to register an element to build.
     *
     * @param mixed $element The element to register
     *
     * @return BuilderInterface
     */
    public function setElement(&$element);

    /**
     * Add.
     *
     * This method allow the builder to build
     * a part of the register element.
     *
     * @param string $property The property to build
     * @param mixed  $data     The data to inject
     * @param array  $options  The options of the build
     *
     * @return BuilderInterface
     */
    public function add($property, $data, array $options = array());

    /**
     * Get result.
     *
     * This method return the result of the
     * building.
     *
     * @return mixed
     */
    public function getResult();
}
