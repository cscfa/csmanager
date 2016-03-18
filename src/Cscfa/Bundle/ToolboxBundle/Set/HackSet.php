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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Set;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Collection\HackInterface;

/**
 * HackSet class.
 *
 * The HackSet class is used to create
 * a set collection.
 *
 * @category Set
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class HackSet implements HackInterface
{
    /**
     * The container.
     *
     * This property register
     * the set elements.
     *
     * @var array
     */
    protected $container;

    /**
     * Default constructor.
     *
     * This property initialize the
     * properties.
     */
    public function __construct()
    {
        $this->container = array();
    }

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
    public function add($key, $value)
    {
        if ($this->hasKey($key)) {
            $this->container[$key]->add($value);
        } else {
            $this->container[$key] = new ListSet();
            $this->container[$key]->add($value);
        }

        return $this;
    }

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
    public function hasKey($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * Get all.
     *
     * This method return all of the keys
     * and it's contained values.
     *
     * @return array
     */
    public function getAll()
    {
        $result = array();
        foreach ($this->getKeys() as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

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
    public function get($key)
    {
        if ($this->hasKey($key)) {
            $ins = $this->container[$key];
            if ($ins instanceof ListSet) {
                return $ins->getAll();
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * Remove in.
     *
     * This method remove a specified value
     * into a key.
     *
     * @param string $key   The key whence remove the value
     * @param mixed  $value The value to remove
     */
    public function removeIn($key, $value)
    {
        if ($this->hasKey($key)) {
            if ($this->hasValueIn($key, $value)) {
                $ins = $this->container[$key];
                if ($ins instanceof ListSet) {
                    $ins->remove($value);
                }
            }
        }
    }

    /**
     * Clear.
     *
     * This method clear all contained
     * data.
     */
    public function clear()
    {
        $this->container = array();
    }

    /**
     * Is empty.
     *
     * This method check if the container
     * is completely empty. Return true
     * if no one key exist.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->container);
    }

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
    public function hasValue($value)
    {
        foreach ($this->getKeys() as $key) {
            $ins = $this->get($key);
            if (in_array($value, $ins)) {
                return $key;
            }
        }

        return;
    }

    /**
     * Get keys.
     *
     * This method return an array
     * that contain each keys.
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->container);
    }

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
    public function hasRecord($key)
    {
        if ($this->hasKey($key)) {
            return !empty($this->get($key));
        } else {
            return true;
        }
    }

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
    public function hasValueIn($key, $value)
    {
        if ($this->hasKey($key)) {
            $ins = $this->container[$key];
            if ($ins instanceof ListSet) {
                return $ins->contain($value);
            }
        } else {
            return false;
        }
    }

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
    public function remove($key)
    {
        if ($this->hasKey($key)) {
            $result = $this->get($key);
            unset($this->container[$key]);

            return $result;
        } else {
            return array();
        }
    }
}
