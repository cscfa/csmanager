<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Manager
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Util\Manager;

use Cscfa\Bundle\SecurityBundle\Entity\Group;
use Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * GroupManager class.
 *
 * The GroupManager class purpose feater to
 * manage a Group entity and it's logic. Also
 * the manager is capable to store and remove
 * an instance into the database.
 *
 * @category Manager
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class GroupManager
{

    /**
     * The group provider service.
     * 
     * This service allow to get
     * specific information and
     * instance from the database.
     * 
     * It will used to validate
     * some things about groups.
     * 
     * @var GroupProvider
     */
    protected $provider;

    /**
     * The doctrinemanager service.
     * 
     * This service allow to persist
     * and remove Group instance from
     * the database.
     * 
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * The role manager service.
     * 
     * This service allow to use
     * the role validations.
     * 
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * The security context.
     *
     * This allow to register the current
     * application user into the User instance
     * as creator or updator.
     *
     * @var Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $security;

    /**
     * The group manager constructor.
     * 
     * This constructor register
     * a group provider that provide
     * access to database store.
     * 
     * @param GroupProvider            $provider        The group provider service
     * @param EntityManager            $doctrineManager The doctrine manager service
     * @param RoleManager              $roleManager     The role manager service
     * @param SecurityContextInterface $security        The security context service
     */
    public function __construct(GroupProvider $provider, EntityManager $doctrineManager, RoleManager $roleManager, SecurityContextInterface $security)
    {
        $this->provider = $provider;
        $this->roleManager = $roleManager;
        $this->entityManager = $doctrineManager;
        $this->security = $security;
    }

    /**
     * Check if a name is valid.
     * 
     * This method allow to check if
     * a given name is valid for a
     * group name usage.
     * 
     * @param string $name The name to check.
     * 
     * @return boolean
     */
    public function nameIsValid($name)
    {
        return preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/", $name) ? true : false;
    }

    /**
     * Check if a name exist.
     * 
     * This method allow to check if a given
     * name already exist for a group.
     * 
     * @param string $name the name to search
     * 
     * @return boolean
     */
    public function nameExist($name)
    {
        return $this->provider->isNameExist($name);
    }

    /**
     * Get new instance.
     * 
     * Get a new instance of
     * a group builder.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder
     */
    public function getNewInstance()
    {
        return new GroupBuilder($this);
    }

    /**
     * Convert a Group instance.
     * 
     * This method allow to convert
     * a group instance into a GroupBuilder
     * instance.
     * 
     * @param Group $group The Group instance to convert
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder
     */
    public function convertInstance(Group $group)
    {
        return new GroupBuilder($this, $group);
    }

    /**
     * This method allow to store a GroupBuilder
     * into the database. A GroupBuilder is a
     * container that encapsulate a Group instance
     * an a StackUpdate instance, so the two
     * objects will be persisted into their own
     * tables.
     *
     * It is possible to only store the StackUpdate
     * object, that allow to remove the Group instance.
     * To do that, it is necessary to pass true as
     * second argument.
     *
     * Considering that use this method to store
     * only the StackUpdate object without remove
     * the Group Object allow to create a Group image
     * that only exist in StackUpdate table.
     * 
     * @param GroupBuilder $groupBuilder The group builder that contain the instance to persist
     * @param boolean      $onlyStack    The state of persisting. True to sotre only the StackUpdate object.
     * 
     * @return void
     */
    public function persist(GroupBuilder $groupBuilder, $onlyStack = false)
    {
        if (! $onlyStack) {
            
            if ($groupBuilder->getId()) {
                $groupBuilder->getGroup()->setUpdatedBy($this->getSecurityUser());
                $groupBuilder->getGroup()->setUpdatedAt(new \DateTime());
            } else {
                $groupBuilder->getGroup()->setCreatedBy($this->getSecurityUser());
                $groupBuilder->getGroup()->setCreatedAt(new \DateTime());
            }
            
            $this->entityManager->persist($groupBuilder->getGroup());
        }
        
        $stack = $groupBuilder->getStackUpdate();
        if ($stack !== null) {
            $stack->setDate(new \DateTime());
            
            $securityUser = $this->getSecurityUser();
            if ($securityUser !== null) {
                $stack->setUpdatedBy($securityUser->getId());
            }
            $this->entityManager->persist($stack);
        }
        
        $this->entityManager->flush();
    }

    /**
     * Remove a Group instance.
     *
     * This method allow to remove a Group
     * instance from the database. It persist
     * the StackUpdate object before processing.
     * 
     * @param GroupBuilder $group The group builder that contain instance to remove from the database.
     *
     * @throws Doctrine\ORM\OptimisticLockException 
     * @return void 
     */
    public function remove(GroupBuilder $group)
    {
        $this->persist($group, true);
        $this->entityManager->remove($group->getGroup());
        $this->entityManager->flush();
    }

    /**
     * Get RoleManager.
     * 
     * This method allow to get the
     * RoleManager to access to the
     * roles validations.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager
     */
    public function getRoleManager()
    {
        return $this->roleManager;
    }
    
    /**
     * Get security user.
     * 
     * This method allow to
     * get the current security
     * user.
     * 
     * @return User|NULL
     */
    protected function getSecurityUser()
    {
        if (method_exists($this->security, "getToken") && $this->security->getToken() !== null && method_exists($this->security->getToken(), "getUser") && $this->security->getToken()->getUser() !== null) {
            return $this->security->getToken()->getUser();
        } else {
            return null;
        }
    }
}
