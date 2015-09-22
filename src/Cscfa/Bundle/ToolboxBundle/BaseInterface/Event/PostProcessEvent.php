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
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;

/**
 * PostProcessEvent interface.
 *
 * The PostProcessEvent
 * interface is used to
 * access to a post process
 * method in a CommandFacade
 * context.
 *
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface PostProcessEvent
{
    /**
     * The post process method.
     * 
     * This method is the post
     * process method that can
     * be used by the 
     * CommandFacade instance.
     * 
     * @param mixed                   $result        The result value
     * @param ErrorRegisteryInterface $to            A builder that is used to apply the value
     * @param array                   $param         The current param array
     * @param CommandFacade           $commandFacade The current CommandFacade instance
     * @param CommandColorFacade      $commandColor  A CommandColorFacade given by the command facade
     */
    public function postProcess($result, ErrorRegisteryInterface &$to, array &$param, CommandFacade $commandFacade, CommandColorFacade $commandColor);
}