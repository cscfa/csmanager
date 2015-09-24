<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Example
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Group
 * @see      Cscfa\Bundle\SecurityBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder
 * @see      Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager
 * @see      Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Repository\GroupRepository
 */
namespace Cscfa\Bundle\SecurityBundle\Example\Group;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\SecurityBundle\Example\ExampleInterface;
use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder;
use Cscfa\Bundle\SecurityBundle\Entity\Role;

/**
 * The HowToCreate class.
 *
 * This class present the Group instance
 * creation.
 *
 * @category Example
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class HowTo extends Controller implements ExampleInterface
{

    /**
     * The howItWork method.
     *
     * This method present the creation
     * of a Group instance.
     *
     * @see    \Cscfa\Bundle\SecurityBundle\Example\ExampleInterface::howItWork()
     * @return void
     */
    public function howItWork()
    {
        // we get a group builder instance
        $groupInstance = $this->getManager()->getNewInstance();
        
        /*
         * here we try to set the group name.
         * It can fail if the name is unavailable
         * or if the name already exist.
         */
        if ($groupInstance->setName("GROUP_TEST")) {
            // here, the name was set
            
            // now we can persist the group instance.
            $this->getManager()->persist($groupInstance);
            unset($groupInstance);
            
            // the provider allow to retreive the group by it name
            $groupInstance = $this->getProvider()->findOneByName("GROUP_TEST");
            
            // we create a new Role instance
            $role = new Role();
            $role->setName("GROUP_ROLE");
            $role->setCreatedAt(new \DateTime());
            
            // here we assign the role to the group
            $groupInstance->addRole($role, true);
            
            // and persist it
            $this->getManager()->persist($groupInstance);
        } else if ($groupInstance->getLastError() === GroupBuilder::INVALID_NAME) {
            // here, the name wasn't set because is unavailable format
        } else if ($groupInstance->getLastError() === GroupBuilder::EXISTING_NAME) {
            // here, the name wasn't set because it already exist
        }
    }

    /**
     * Get the manager.
     * 
     * This method allow 
     * to retreive the 
     * GroupManager service.
     * 
     * @return GroupManager
     */
    public function getManager()
    {
        return $this->get("core.manager.group_manager");
    }

    /**
     * Get the provider.
     * 
     * This method allow
     * to retreive the
     * GroupProvider service.
     * 
     * @return GroupProvider
     */
    public function getProvider()
    {
        return $this->get("core.provider.group_provider");
    }
}
