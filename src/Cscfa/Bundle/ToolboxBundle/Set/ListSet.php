<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Set
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Set;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface;

/**
 * ListSet class.
 *
 * The ListSet class is used to create 
 * a set collection.
 *
 * @category Set
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ListSet implements SetInterface
{
    /**
     * The set container.
     * 
     * This property is an
     * array that contain
     * the values.
     * 
     * @var array
     */
    protected $container;

    /**
     * Default constructor.
     * 
     * This constructor initialize the
     * properties.
     */
    public function __construct()
    {
        $this->container = array();
    }

    /**
     * Add
     * 
     * add an element to the set.
     * 
     * @param mixed $element The element to add
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::add()
     * @return void
     */
    public function add($element)
    {
        $this->container[] = $element;
    }

    /**
     * Remove all
     * 
     * Remove all contained elements
     * by a set from the current set.
     * 
     * @param array $elements The elements list to remove
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::removeAll()
     * @return void
     */
    public function removeAll(array $elements)
    {
        foreach ($elements as $element) {
            $this->remove($element);
        }
    }

    /**
     * Contain
     * 
     * Check if the set contain
     * a specified element.
     * 
     * @param mixed $element The element to check
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::contain()
     * @return boolean
     */
    public function contain($element)
    {
        return in_array($element, $this->container);
    }

    /**
     * Get all
     * 
     * Get all of the set
     * contained elements.
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::getAll()
     * @return array
     */
    public function getAll()
    {
        return $this->container;
    }

    /**
     * Add all
     * 
     * add an array of elements 
     * to the set.
     * 
     * @param array $elements The array of elements to add
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::addAll()
     * @return void
     */
    public function addAll(array $elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Contain all
     * 
     * Check if the set contain all
     * of the elements of an other set.
     * 
     * @param array $elements The element list to check
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::containsAll()
     * @return boolean
     */
    public function containsAll(array $elements)
    {
        foreach ($elements as $element) {
            if (! $this->contain($element)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get
     * 
     * Get an element from
     * the set.
     * 
     * @param mixed $element The element to get
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::get()
     * @return mixed
     */
    public function get($element)
    {
        if (isset($this->container[$element])) {
            return $this->container[$element];
        } else if (in_array($element, $this->container)) {
            $key = array_search($element, $this->container);
            return $this->container[$key];
        } else {
            return null;
        }
    }

    /**
     * Clear
     * 
     * Remove all elements from
     * this set.
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::clear()
     * @return void
     */
    public function clear()
    {
        $this->container = array();
    }

    /**
     * Is empty
     * 
     * Check if the set is
     * empty.
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::isEmpty()
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->container);
    }

    /**
     * Remove
     * 
     * Remove an element from
     * the set and return it.
     * 
     * @param mixed $element The element to remove
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface::remove()
     * @return void
     */
    public function remove($element)
    {
        if (isset($this->container[$element])) {
            $element = $this->container[$element];
            unset($this->container[$element]);
            return $element;
        } else if (in_array($element, $this->container)) {
            $key = array_search($element, $this->container);
            $element = $this->container[$key];
            unset($this->container[$key]);
            return $element;
        } else {
            return null;
        }
    }
}
