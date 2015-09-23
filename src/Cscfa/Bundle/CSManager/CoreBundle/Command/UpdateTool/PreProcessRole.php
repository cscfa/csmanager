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

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PreProcessEventInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;

/**
 * PreProcessRole class.
 *
 * The PreProcessRole class purpose feater to
 * pre process a role set.
 *
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PreProcessRole implements PreProcessEventInterface
{
    /**
     * The provider method.
     * 
     * This represent the method
     * of the provider to use
     * to get all instance
     * selector.
     * 
     * @var string
     */
    protected $providerMethod;
    
    /**
     * Default constructor.
     * 
     * This constructor allow to
     * register the method to use
     * with the provider.
     * 
     * @param string $providerMethod The provider method to use to get the roles instances
     */
    public function __construct($providerMethod)
    {
        $this->providerMethod = $providerMethod;
    }
    
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
     * @return array
     */
    public function preProcess(array &$param, CommandFacade $commandFacade)
    {
        $roles = $param["extra"]->{$this->providerMethod}();
        if (empty($roles)) {
            $param["active"] = false;
        } else {
            $param["ask"]["limit"] = $roles;
            $param["extraNames"] = $roles;
            $param["active"] = true;
        }
        
        return $param;
    }
}
