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
namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Event;

use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;

/**
 * ErrorRegisteryInterface interface.
 *
 * The ErrorRegisteryInterface
 * interface is used to access
 * to a last error parameter.
 *
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface PreProcessEventInterface
{
    /**
     * The pre process method.
     * 
     * This method is the pre
     * process method that can
     * be used by the 
     * CommandFacade instance.
     * 
     * @param array         &$param        The current param array
     * @param CommandFacade $commandFacade The current CommandFacade instance
     * 
     * @return void
     */
    public function preProcess(array &$param, CommandFacade $commandFacade);
}
