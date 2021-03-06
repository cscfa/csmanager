<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Command\UpdateTool;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PostProcessEventInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;

/**
 * PostProcessRole class.
 *
 * The PostProcessRole class purpose feater to
 * post process a role set.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PostProcessRole implements PostProcessEventInterface
{

    /**
     * The setter method.
     * 
     * This represent the
     * method of the builder
     * to use to set the
     * role instance.
     * 
     * @var string
     */
    protected $setterMethod;
    
    /**
     * Default constructor.
     * 
     * This constructor allow
     * to specify the setter
     * method name to use on
     * the builder to set the
     * role instance.
     * 
     * @param string $setterMethod The setter method to use to insert role
     */
    public function __construct($setterMethod = "setRole")
    {
        $this->setterMethod = $setterMethod;
    }
    
    /**
     * The post process method.
     *
     * This method is the post
     * process method that can
     * be used by the
     * CommandFacade instance.
     *
     * @param mixed                   $result        The result value
     * @param ErrorRegisteryInterface &$to           A builder that is used to apply the value
     * @param array                   &$param        The current param array
     * @param CommandFacade           $commandFacade The current CommandFacade instance
     * @param CommandColorFacade      $commandColor  A CommandColorFacade given by the command facade
     * 
     * @return void
     */
    public function postProcess($result, ErrorRegisteryInterface &$to, array &$param, CommandFacade $commandFacade, CommandColorFacade $commandColor)
    {
        $role = null;
        $provider = $param["extra"];
        $rolesNames = $param["extraNames"];
        
        if ($result !== null) {
            if (array_key_exists($result, $rolesNames)) {
                $tmpR = $provider->findOneByName($rolesNames[$result]);
                
                if ($tmpR instanceof RoleBuilder) {
                    $role = $tmpR->getRole();
                } else {
                    $this->printError($commandColor, $param);
                }
            }
            if (! $to->{$this->setterMethod}($role)) {
                $this->printError($commandColor, $param);
            } else {
                $this->printSuccess($commandColor, $param);
            }
        } else {
            if (! $to->{$this->setterMethod}(null)) {
                $this->printError($commandColor, $param);
            } else {
                $this->printSuccess($commandColor, $param);
            }
        }
        $param["active"] = false;
    }
    
    /**
     * Print success.
     * 
     * This method allow to
     * display the success
     * message.
     * 
     * @param CommandColorFacade $commandColor The current command facade that dislay the success message
     * @param array              $param        The current parameter array
     * 
     * @return void
     */
    protected function printSuccess($commandColor, $param)
    {
        $commandColor->clear();
        $commandColor->addText("\n");
        $commandColor->addText($param["success"], "success");
        $commandColor->addText("\n");
        $commandColor->write();
    }

    /**
     * Print error.
     *
     * This method allow to
     * display the error
     * message.
     *
     * @param CommandColorFacade $commandColor The current command facade that dislay the error message
     * @param array              $param        The current parameter array
     * 
     * @return void
     */
    protected function printError($commandColor, $param)
    {
        $commandColor->clear();
        $commandColor->addText("\n");
        $commandColor->addText($param["failure"], "failure");
        $commandColor->addText("\n");
        $commandColor->write();
    }
}
