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

/**
 * ObjectsContainer.
 *
 * The ObjectsContainer is used to store and give
 * controller specific objects.
 *
 * The objects are stored into key:value array.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ObjectsContainer
{
    /**
     * Objects.
     *
     * The key:value array that store
     * the given objects.
     *
     * @var array
     */
    protected $objects = array();

    /**
     * Constructor.
     *
     * This constructor take an array that
     * define the objects to insert with
     * alias as keys and object as value.
     *
     * @param array $objects The objects to insert as array with alias:object as key:value
     *
     * @example new ObjectsContainer(array("std"=>(new \stdClass()))
     */
    public function __construct(array $objects = array())
    {
        foreach ($objects as $alias => $object) {
            $this->addObject($object, $alias);
        }
    }

    /**
     * Add object.
     *
     * This method allow to insert an object
     * into the stored objects array. It inserted
     * into the array key named as the alias argument.
     *
     * @param object $object The object to insert
     * @param string $alias  The aliased key to use
     *
     * @throws \Exception This method throw a code 500 exception if the given
     *                    object is not an object instance.
     *
     * @return ObjectsContainer
     */
    public function addObject($object, $alias)
    {
        if (!is_object($object) && $object !== null) {
            $message = sprintf(
                'An instance of %s was passed to ObjectContainer to be stored. Only object can be stored.',
                gettype($object)
            );
            throw new \Exception($message, 500);
        }

        $this->objects[$alias] = $object;

        return $this;
    }

    /**
     * Has object.
     *
     * This method check if an aliased object already
     * exist into the stored objects array. It return true
     * if isset, if not, return false.
     *
     * @param string $alias The aliased object alias to search
     *
     * @return bool
     */
    public function hasObject($alias)
    {
        return array_key_exists($alias, $this->objects);
    }

    /**
     * Get object.
     *
     * This method return on object by it's storage alias
     * or null if no objects are registered into it's
     * alias.
     *
     * @param string $alias The onject alias to get
     *
     * @return object|null
     */
    public function getObject($alias)
    {
        if ($this->hasObject($alias)) {
            return $this->objects[$alias];
        }

        return;
    }
}
