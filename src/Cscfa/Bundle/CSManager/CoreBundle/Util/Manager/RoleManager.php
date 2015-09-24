<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Manager
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Manager;

use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Exception\CircularReferenceException;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;

/**
 * RoleManager class.
 *
 * The RoleManager class purpose feater to
 * manage a Role entity and it's logic. Also
 * the manager is capable to store and remove
 * an instance into the database.
 *
 * @category Manager
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleManager
{

    /**
     * The entity manager.
     *
     * This allow to register or remove the
     * current Role instance into the database.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * The RoleProvider.
     *
     * This variable is used to get
     * Role instance from the database.
     *
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider
     */
    protected $roleProvider;

    /**
     * The security context.
     *
     * This allow to register the current
     * application user into the Role instance
     * as creator or updator.
     *
     * @var Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $security;

    /**
     * RoleManager constructor.
     *
     * This constructor allow to store role
     * provider and security context for
     * later use.
     *
     * @param EntityManager            $entityManager The entity manager to use to interact with database.
     * @param RoleProvider             $roleProvider  The role provider to use to get Role instances.
     * @param SecurityContextInterface $security      The security context to use to get current user.
     */
    public function __construct(EntityManager $entityManager, RoleProvider $roleProvider, SecurityContextInterface $security)
    {
        $this->entityManager = $entityManager;
        $this->roleProvider = $roleProvider;
        $this->security = $security;
    }

    /**
     * Check if role exist.
     *
     * This method allow to check if a role
     * already exist in the database by search
     * it own role name. It will return true
     * if exist or false if not.
     *
     * @param string $roleName The new Role name
     *          
     * @return boolean
     */
    public function roleExists($roleName)
    {
        return $this->roleProvider->isExistingByName($roleName);
    }

    /**
     * Check for circular reference.
     *
     * This method allow to valid that
     * a Role never create a circular
     * reference with one of his child.
     *
     * @param string|Role|RoleBuilder $roleReference The role reference to check.
     *           
     * @return boolean
     */
    public function hasCircularReference($roleReference)
    {
        if (is_string($roleReference)) {
            $role = $this->roleProvider->findOneByName($roleReference);
        } else if ($roleReference instanceof Role) {
            $role = $roleReference;
        } else if ($roleReference instanceof RoleBuilder) {
            $role = $roleReference->getRole();
        } else {
            return false;
        }
        
        if ($role === null) {
            return true;
        }
        
        $roles = array(
            $role->getName()
        );
        
        while ($role->getChild()) {
            $role = $role->getChild();
            if (in_array($role->getName(), $roles)) {
                return true;
            }
            $roles[] = $role->getName();
        }
        
        return false;
    }

    /**
     * Get roles wire.
     *
     * This method allow to retreive all
     * of the roles name from the requested
     * named Role to his last child.
     *
     * Also, a check for circular reference
     * is perform before search.
     *
     * @param string $roleName The head role name.
     * 
     * @throws CircularReferenceException
     * @return string[]|NULL
     */
    public function getRoleWire($roleName)
    {
        if (! $this->hasCircularReference($roleName)) {
            
            $role = $this->roleProvider->findOneByName($roleName);
            $roles = array(
                $role->getName()
            );
            
            while ($role->getChild()) {
                $role = $role->getChild();
                $roles[] = $role->getName();
            }
        } else {
            Throw new CircularReferenceException($this->roleProvider->findOneByName($roleName));
        }
        
        return $roles;
    }

    /**
     * Check if a name is valid for a Role.
     *
     * This method is a pure fonctional thing
     * to valid that a Role name respect the
     * usual role format.
     *
     * The usual role format is defined by
     * the folowing regex : ^[a-zA-Z_]+$,
     * so only alphabetics characters without
     * accent, and undersore are allowed.
     *
     * @param string $name The string to check.
     * 
     * @return number
     */
    public function nameIsValid($name)
    {
        return preg_match("/^[a-zA-Z_]+$/", $name) == 1 ? true : false;
    }

    /**
     * Get Roles name.
     *
     * This methhod allow to get all of the
     * name of roles from the database into
     * an array of string.
     *
     * @return string[]
     */
    public function getRolesName()
    {
        $roles = $this->roleProvider->findAll();
        $names = array();
        foreach ($roles as $role) {
            $names[] = $role->getName();
        }
        
        return $names;
    }

    /**
     * Get a new instance of RoleBuilder.
     *
     * This method create and return a new
     * instance of RoleBuilder.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder
     */
    public function getNewInstance()
    {
        return new RoleBuilder($this);
    }

    /**
     * Persist a RoleBuilder.
     *
     * This method allow to store a RoleBuilder
     * into the database. A RoleBuilder is a
     * container that encapsulate a Role instance
     * an a StackUpdate instance, so the two
     * objects will be persisted into their own
     * tables.
     *
     * It is possible to only store the StackUpdate
     * object, that allow to remove the Role instance.
     * To do that, it is necessary to pass true as
     * second argument.
     *
     * Considering that use this method to store
     * only the StackUpdate object without remove
     * the Role Object allow to create a Role image
     * that only exist in StackUpdate table.
     * 
     * @param RoleBuilder $roleBuilder The role builder that contain instances to store.
     * @param boolean     $onlyStack   The state of persisting. True to sotre only the StackUpdate object.
     *
     * @throws Doctrine\ORM\OptimisticLockException
     * @return void
     */
    public function persist(RoleBuilder $roleBuilder, $onlyStack = false)
    {
        if (! $onlyStack) {
            
            if ($roleBuilder->getId()) {
                $roleBuilder->getRole()->setUpdatedBy($this->getSecurityUser());
                $roleBuilder->getRole()->setUpdatedAt(new \DateTime());
            } else {
                $roleBuilder->getRole()->setCreatedBy($this->getSecurityUser());
                $roleBuilder->getRole()->setCreatedAt(new \DateTime());
            }
            
            $this->entityManager->persist($roleBuilder->getRole());
        }
        
        $stack = $roleBuilder->getStackUpdate();
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
     * Remove a Role instance.
     *
     * This method allow to remove a Role
     * instance from the database. It persist
     * the StackUpdate object before processing.
     * 
     * @param RoleBuilder $role The role builder that contain instance to remove from the database.
     *
     * @throws Doctrine\ORM\OptimisticLockException 
     * @return void 
     */
    public function remove(RoleBuilder $role)
    {
        $this->persist($role, true);
        $this->entityManager->remove($role->getRole());
        $this->entityManager->flush();
    }

    /**
     * Convert instance of Role.
     *
     * This method allow to convert a Role
     * instance into RoleBuilder instance.
     *
     * This allow to store a new StackUpdate
     * before or after modifications.
     *
     * Consider that this method can be called
     * everywhere and allow to store a new
     * StackUpdate with the calling momentRole
     * image.
     *
     * @param Role $role The role instance to convert.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder
     */
    public function convertInstance(Role $role)
    {
        return new RoleBuilder($this, $role);
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
