<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Builders\Abstracts;

use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderInterface;

/**
 * AbstractBuilder.
 *
 * The AbstractBuilder create default process for
 * builder instance.
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * Element.
     *
     * This property store the element
     * to build.
     *
     * @var mixed
     */
    protected $element;

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
    abstract public function add($property, $data, array $options = array());

    /**
     * Get result.
     *
     * This method return the result of the
     * building.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->element;
    }

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
    public function setElement(&$element)
    {
        $this->element = $element;

        return $this;
    }
}
