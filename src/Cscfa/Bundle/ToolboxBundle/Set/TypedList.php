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
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;

/**
 * TypedList class.
 *
 * The TypedList class is used to create 
 * a set collection constrained to a 
 * specifical type.
 *
 * @category Set
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class TypedList implements SetInterface
{

    /**
     * The constraint type.
     * 
     * This property inform
     * on the type constraint.
     * 
     * @var string
     */
    protected $type;

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
     * This constructor constraint the type
     * of the set values by passing a string
     * or an object as parameter.
     * 
     * @param mixed $type the supported type constraint.
     * 
     * @throws UnexpectedTypeException if the given type is not a string value or an object
     */
    public function __construct($type)
    {
        if (is_string($type)) {
            $this->type = $type;
        } else if (is_object($type)) {
            $this->type = get_class($type);
        } else {
            throw new UnexpectedTypeException("TypedList need an object as type.", 500);
        }
        
        $this->container = array();
    }

    /**
     * Test if support class.
     * 
     * This method check if a given
     * class is currently supported
     * by the set.
     * 
     * @param mixed $class The class to test
     * 
     * @return boolean
     */
    protected function supportClass($class)
    {
        if (is_string($class) && $class === $this->type) {
            return true;
        } else if (is_object($class) && get_class($class) === $this->type) {
            return true;
        }
        
        return false;
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
        if ($this->supportClass($element)) {
            $this->container[] = $element;
        }
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
    public function removeAll($elements)
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
    public function addAll($elements)
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
    public function containsAll($elements)
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
