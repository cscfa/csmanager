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

namespace Cscfa\Bundle\TwigUIBundle\Interfaces;

use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;

/**
 * ModuleSetInterface.
 *
 * The ModuleSetInterface is used to define
 * the module set methods to iterate over
 * a ModuleInterface set.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ModuleSetInterface extends \Iterator
{
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
     */
    public function addModule(ModuleInterface $module);

    /**
     * Process all.
     *
     * This method allow to process
     * all of the registered modules.
     *
     * @param EnvironmentContainer $environment The current environment
     *
     * @return ModuleSetInterface
     */
    public function processAll(EnvironmentContainer $environment);

    /**
     * To array.
     *
     * This method return the registered
     * modules as array.
     *
     * @return array
     */
    public function toArray();
}
