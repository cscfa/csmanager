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

namespace Cscfa\Bundle\TwigUIBundle\Modules;

use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleInterface;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;

/**
 * ModuleSet.
 *
 * The ModuleSet is used to manage
 * a set of module.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ModuleSet implements ModuleSetInterface
{
    /**
     * Modules.
     *
     * This property register the
     * modules of the module set.
     *
     * @var array
     */
    protected $modules = array();

    /**
     * Position.
     *
     * The iterator cursor position.
     *
     * @var int
     */
    protected $position;

    /**
     * Next.
     *
     * Move forward to next element
     *
     * @see Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Valid.
     *
     * Checks if current position is valid
     *
     * @see Iterator::valid()
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->modules);
    }

    /**
     * Current.
     *
     * Return the current element
     *
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->modules[$this->position];
    }

    /**
     * Rewind.
     *
     * Rewind the Iterator to the first element
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * To array.
     *
     * This method return the registered
     * modules as array.
     *
     * @return array
     *
     * @see \Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface::toArray()
     */
    public function toArray()
    {
        return $this->modules;
    }

    /**
     * Process all.
     *
     * This method allow to process
     * all of the registered modules.
     *
     * @param EnvironmentContainer $environment
     *
     * @return ModuleSetInterface
     *
     * @see \Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface::processAll()
     */
    public function processAll(EnvironmentContainer $environment)
    {
        $this->sort();
        foreach ($this as $module) {
            $module->process($environment);
        }

        return $this;
    }

    /**
     * Add module.
     *
     * This method allow to register
     * a new module into the module
     * set.
     *
     * @param ModuleInterface $module The module to register
     *
     * @return ModuleSetInterface
     *
     * @see \Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface::addModule()
     */
    public function addModule(ModuleInterface $module)
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * Key.
     *
     * Return the key of the current element
     *
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Sort.
     *
     * This method allow the module set
     * to sort it module storage array.
     */
    protected function sort()
    {
        @usort($this->modules, array($this, 'sortProcess'));
    }

    /**
     * Sort process.
     *
     * This method contain the sort
     * method of the module set.
     *
     * @param ModuleInterface $first  The first element to compare
     * @param ModuleInterface $second The second element to compare
     *
     * @return int
     */
    protected function sortProcess(ModuleInterface $first, ModuleInterface $second)
    {
        if ($first->getPriority() == $second->getPriority()) {
            return 0;
        }

        return ($first->getPriority() < $second->getPriority()) ? -1 : 1;
    }
}
