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

use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
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
class TypedList extends ListSet
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
        } else if (!is_object($element) && in_array($element, $this->container)) {
            $key = array_search($element, $this->container);
            return $this->container[$key];
        } else {
            return null;
        }
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
        if (in_array($element, $this->container)) {
            $key = array_search($element, $this->container);
            $element = $this->container[$key];
            unset($this->container[$key]);
            return $element;
        } else if (!is_object($element) && isset($this->container[$element])) {
            $element = $this->container[$element];
            unset($this->container[$element]);
            return $element;
        } else {
            return null;
        }
    }
}
