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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection;

/**
 * HackInterface interface.
 *
 * The HackInterface interface
 * is used to create a set type
 * collection.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface HackInterface
{
    /**
     * Add.
     *
     * This method allow to insert a value
     * into a key. If the key doesn't exist,
     * will be created.
     *
     * @param string $key   The key where insert
     * @param mixed  $value The value to insert
     *
     * @return mixed.
     */
    public function add($key, $value);

    /**
     * Clear.
     *
     * This method clear all contained
     * data.
     */
    public function clear();

    /**
     * Has key.
     *
     * This method check if a given
     * key already exist into the
     * set.
     *
     * @param string $key The key to search.
     *
     * @return boolean.
     */
    public function hasKey($key);

    /**
     * Has value.
     *
     * This method search a specified value
     * and return the key if exist, or null.
     *
     * @param mixed $value The value to find.
     *
     * @return string|null
     */
    public function hasValue($value);

    /**
     * Has value in.
     *
     * This method search a specified value
     * into a specified key element and return
     * true if exist.
     *
     * @param string $key   The key where search
     * @param mixed  $value The value to search
     *
     * @return bool
     */
    public function hasValueIn($key, $value);

    /**
     * Get.
     *
     * This method return the values contained
     * into a key.
     *
     * @param string $key The named container to return
     *
     * @return array
     */
    public function get($key);

    /**
     * Get all.
     *
     * This method return all of the keys
     * and it's contained values.
     *
     * @return array
     */
    public function getAll();

    /**
     * Is empty.
     *
     * This method check if the container
     * is completely empty. Return true
     * if no one key exist.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Has record.
     *
     * This method check if a given key
     * contain value. Return true if no
     * one value is registered.
     *
     * @param string $key The key to test.
     *
     * @return bool
     */
    public function hasRecord($key);

    /**
     * Remove.
     *
     * This method remove a specified key
     * and return it's values.
     *
     * @param string $key The key to remove.
     *
     * @return array
     */
    public function remove($key);

    /**
     * Remove in.
     *
     * This method remove a specified value
     * into a key.
     *
     * @param string $key   The key whence remove the value
     * @param mixed  $value The value to remove
     */
    public function removeIn($key, $value);

    /**
     * Get keys.
     *
     * This method return an array
     * that contain each keys.
     *
     * @return array
     */
    public function getKeys();
}
