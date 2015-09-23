<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Command\UpdateTool;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PostProcessEventInterface;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;

/**
 * PostProcessRoleArray class.
 *
 * The PostProcessRoleArray class purpose feater to
 * post process a role set.
 *
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PostProcessRoleArray implements PostProcessEventInterface
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
     * @param ErrorRegisteryInterface &$to           A builder that is used to apply the value
     * @param array                   &$param        The current param array
     * @param CommandFacade           $commandFacade The current CommandFacade instance
     * @param CommandColorFacade      $commandColor  A CommandColorFacade given by the command facade
     * 
     * @return void
     */
    public function postProcess($result, ErrorRegisteryInterface &$to, array &$param, CommandFacade $commandFacade, CommandColorFacade $commandColor)
    {
        $roles = array();
        $provider = $param["extra"];
        $rolesNames = $param["extraNames"];
        $boolSuccess = true;
        
        if ($result !== null) {
            if (is_array($result)) {
                foreach ($result as $value) {
                    if (array_key_exists($value, $rolesNames)) {
                        $tmpR = $provider->findOneByName($rolesNames[$value]);
                        
                        if ($tmpR instanceof RoleBuilder) {
                            $roles[] = $tmpR->getRole();
                        }
                    }
                }
            } else if (array_key_exists($result, $rolesNames)) {
                $tmpR = $provider->findOneByName($rolesNames[$result]);
                
                if ($tmpR instanceof RoleBuilder) {
                    $roles[] = $tmpR->getRole();
                }
            }
            
            foreach ($roles as $role) {
                if (! $to->addRole($role)) {
                    $boolSuccess = false;
                }
            }
        } else {
            foreach ($to->getRoles() as $role) {
                if ($role instanceof Role) {
                    $to->removeRole($role);
                } else if (is_string($role)) {
                    $tmpR = $provider->findOneByName($role);
                    
                    if ($tmpR instanceof RoleBuilder) {
                        $to->removeRole($tmpR->getRole());
                    } else if ($tmpR instanceof Role) {
                        $to->removeRole($tmpR);
                    }
                }
            }
        }
        
        if (! $boolSuccess) {
            $commandColor->clear();
            $commandColor->addText("\n");
            $commandColor->addText($param["failure"], "failure");
            $commandColor->addText("\n");
            $commandColor->write();
        } else {
            $commandColor->clear();
            $commandColor->addText("\n");
            $commandColor->addText($param["success"], "success");
            $commandColor->addText("\n");
            $commandColor->write();
        }
        
        $param["active"] = false;
    }
}
