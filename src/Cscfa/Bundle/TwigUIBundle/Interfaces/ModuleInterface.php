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
 * ModuleInterface.
 *
 * The ModuleInterface is used to define
 * the module methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ModuleInterface
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
     * @return ModuleInterface
     */
    public function addModule(ModuleInterface $module);

    /**
     * Get module.
     *
     * This method return the modules
     * contained by the current module.
     *
     * @return ModuleSetInterface
     */
    public function getModules();

    /**
     * Process.
     *
     * This method process the module by
     * calling the render method.
     *
     * @param EnvironmentContainer $environment The current environment
     *
     * @return ModuleInterface
     */
    public function process(EnvironmentContainer $environment);

    /**
     * Render.
     *
     * This method run the module
     * process. It return a TwigRequest
     * if needed or null.
     *
     * @param EnvironmentContainer $environment The current environment
     *
     * @return TwigRequest|null
     */
    public function render(EnvironmentContainer $environment);

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
    public function setPriority($priority);

    /**
     * Get priority.
     *
     * This method return the priority
     * of the module.
     *
     * @return float
     */
    public function getPriority();

    /**
     * Get name.
     *
     * This method return the name
     * of the module.
     *
     * @return string
     */
    public function getName();
}
