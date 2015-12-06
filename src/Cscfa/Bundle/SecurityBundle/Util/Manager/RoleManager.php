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

use Cscfa\Bundle\SecurityBundle\Entity\Role;
use Cscfa\Bundle\SecurityBundle\Exception\CircularReferenceException;
use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * RoleManager class.
 *
 * The RoleManager class purpose feater to
 * manage a Role entity and it's logic. Also
 * the manager is capable to store and remove
 * an instance into the database.
 *
 * @category Manager
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleManager
{
    
    /**
     * The current service container
     * 
     * This container is used to get
     * other services from the container.
     * 
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RoleManager constructor.
     * 
     * This constructor register the service container
     * to allow retreiving serices.
     *
     * @param ContainerInterface ContainerInterface The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $provider = $this->container->get("core.provider.role_provider");
        return $provider->isExistingByName($roleName);
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
        $provider = $this->container->get("core.provider.role_provider");
        
        if (is_string($roleReference)) {
            $role = $provider->findOneByName($roleReference);
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
        $provider = $this->container->get("core.provider.role_provider");
        
        if (! $this->hasCircularReference($roleName)) {
            
            $role = $provider->findOneByName($roleName);
            $roles = array(
                $role->getName()
            );
            
            while ($role->getChild()) {
                $role = $role->getChild();
                $roles[] = $role->getName();
            }
        } else {
            Throw new CircularReferenceException($provider->findOneByName($roleName));
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
        $provider = $this->container->get("core.provider.role_provider");
        
        $roles = $provider->findAll();
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
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder
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
        $manager = $this->container->get("doctrine.orm.entity_manager");
        
        if (! $onlyStack) {
            
            if ($roleBuilder->getId()) {
                $roleBuilder->getRole()->setUpdatedBy($this->getSecurityUser());
                $roleBuilder->getRole()->setUpdatedAt(new \DateTime());
            } else {
                $roleBuilder->getRole()->setCreatedBy($this->getSecurityUser());
                $roleBuilder->getRole()->setCreatedAt(new \DateTime());
            }
            
            $manager->persist($roleBuilder->getRole());
        }
        
        $stack = $roleBuilder->getStackUpdate();
        if ($stack !== null) {
            $stack->setDate(new \DateTime());

            $securityUser = $this->getSecurityUser();
            if ($securityUser !== null) {
                $stack->setUpdatedBy($securityUser->getId());
            }
            $manager->persist($stack);
        }
        
        $manager->flush();
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
        $manager = $this->container->get("doctrine.orm.entity_manager");
        
        $this->persist($role, true);
        $manager->remove($role->getRole());
        $manager->flush();
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
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder
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
        $security = $this->container->get('security.context');
        
        if (method_exists($security, "getToken") && $security->getToken() !== null && method_exists($security->getToken(), "getUser") && $security->getToken()->getUser() !== null) {
            return $security->getToken()->getUser();
        } else {
            return null;
        }
    }
}
