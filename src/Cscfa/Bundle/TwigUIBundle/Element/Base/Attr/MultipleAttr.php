<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Set
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Base\Attr;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\SetInterface;

/**
 * MultipleAttr class.
 *
 * The MultipleAttr class
 * is used to register an
 * attribute with multiple 
 * values.
 *
 * @category Set
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class MultipleAttr implements SetInterface
{

    /**
     * The values.
     * 
     * This property indicate the
     * set of values that is 
     * registered for an attribute.
     * 
     * @var array
     */
    protected $content;

    /**
     * Default constructor.
     * 
     * This contructor initialize the
     * properties.
     */
    public function __construct()
    {
        $this->content = array();
    }

    /**
     * Add
     *
     * add an element to the set.
     *
     * @param mixed $element The element to add
     *
     * @return MultipleAttr
     */
    public function add($element)
    {
        $this->content[] = $element;
        
        return $this;
    }

    /**
     * Remove all
     *
     * Remove all contained elements
     * by a set from the current set.
     *
     * @param array $elements The elements list to remove
     *
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
     * @return boolean
     */
    public function contain($element)
    {
        return in_array($element, $this->content);
    }

    /**
     * Get all
     *
     * Get all of the set
     * contained elements.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->content;
    }

    /**
     * Add all
     *
     * add an array of elements
     * to the set.
     *
     * @param array $elements The array of elements to add
     *
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
     * @return mixed
     */
    public function get($element)
    {
        if (isset($this->content[$element])) {
            return $this->content[$element];
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
     * @return void
     */
    public function clear()
    {
        $this->content = array();
    }

    /**
     * Is empty
     *
     * Check if the set is
     * empty.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->content);
    }

    /**
     * Remove
     *
     * Remove an element from
     * the set and return it.
     *
     * @param mixed $element The element to remove
     *
     * @return mixed
     */
    public function remove($element)
    {
        if (isset($this->content[$element])) {
            $content = $this->content[$element];
            unset($this->content[$element]);
            return $content;
        } else {
            return null;
        }
    }
    
    /**
     * To string.
     * 
     * This method return the current
     * instance parsed as string.
     * 
     * @return string
     */
    public function __toString()
    {
        return implode(" ", $this->getAll());
    }
}
