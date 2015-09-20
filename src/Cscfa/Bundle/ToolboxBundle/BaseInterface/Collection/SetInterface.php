<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection;

/**
 * SetInterface interface.
 *
 * The SetInterface interface
 * is used to create a set type
 * collection.
 *
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface SetInterface
{

    /**
     * Add
     * 
     * add an element to the set.
     * 
     * @param mixed $element The element to add
     * 
     * @return void
     */
    public function add($element);

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
    public function addAll(array $elements);

    /**
     * Clear
     * 
     * Remove all elements from
     * this set.
     * 
     * @return void
     */
    public function clear();

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
    public function contain($element);

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
    public function containsAll(array $elements);

    /**
     * Is empty
     * 
     * Check if the set is
     * empty.
     * 
     * @return boolean
     */
    public function isEmpty();

    /**
     * Remove
     * 
     * Remove an element from
     * the set and return it.
     * 
     * @param mixed $element The element to remove
     * 
     * @return void
     */
    public function remove($element);

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
    public function removeAll(array $elements);

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
    public function get($element);

    /**
     * Get all
     * 
     * Get all of the set
     * contained elements.
     * 
     * @return array
     */
    public function getAll();
}
