<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Modules;

use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleInterface;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;

/**
 * AbstractModule.
 *
 * The AbstractModule is used to define
 * the process of the defaults module
 * methods.
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractModule implements ModuleInterface
{
    /**
     * Child modules.
     *
     * This property store the childs
     * modules of the current.
     *
     * @var ModuleSetInterface
     */
    protected $childModules;

    /**
     * Priority.
     *
     * This property store the current
     * module priority.
     *
     * @var float
     */
    protected $priority;

    /**
     * Constructor.
     *
     * The default AbstractModule
     * constructor.
     */
    public function __construct()
    {
        $this->childModules = new ModuleSet();
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
     * @return ModuleInterface
     */
    public function addModule(ModuleInterface $module)
    {
        $this->childModules->addModule($module);

        return $this;
    }

    /**
     * Get module.
     *
     * This method return the modules
     * contained by the current module.
     *
     * @return ModuleSetInterface
     */
    public function getModules()
    {
        return $this->childModules;
    }

    /**
     * Set priority.
     *
     * This method set the current
     * module priority.
     *
     * @param float $priority The module priority
     *
     * @return ModuleInterface
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * This method return the priority
     * of the module.
     *
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
