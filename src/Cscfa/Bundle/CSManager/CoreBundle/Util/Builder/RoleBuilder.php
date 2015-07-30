<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Builder
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Builder;

use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;

/**
 * RoleBuilder class.
 *
 * The RoleBuilder class purpose feater to
 * manage a Role entity with a StackUpdate
 * object into the database behind the
 * RoleManager usage.
 *
 * @category Builder
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
 */
class RoleBuilder
{

    /**
     * A RoleBuilder error type.
     *
     * This constant represent a no
     * error state of RoleBuilder.
     *
     * The default value of this constant
     * is an integer set to -1.
     *
     * @var integer
     */
    const NO_ERROR = - 1;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setName method
     * and inform that the given role
     * name already exist.
     *
     * The default value of this constant
     * is an integer set to 0.
     *
     * @var integer
     */
    const DUPLICATE_ROLE_NAME = 0;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setName method
     * and inform that the given role
     * name is invalid into it format.
     *
     * The default value of this constant
     * is an integer set to 1.
     *
     * @var integer
     */
    const INVALID_ROLE_NAME = 1;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setChild method
     * and inform that the given role
     * child have a mismatch instance.
     *
     * The default value of this constant
     * is an integer set to 2.
     *
     * @var integer
     */
    const INVALID_ROLE_INSTANCE_OF = 2;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setChild method
     * and inform that the given role
     * child create a circular reference
     * to another role.
     *
     * The default value of this constant
     * is an integer set to 3.
     *
     * @var integer
     */
    const CIRCULAR_REFERENCE = 3;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setCreatedAt
     * method and inform that the given
     * DateTime instance represent a date
     * after the present day and time.
     *
     * The default value of this constant
     * is an integer set to 4.
     *
     * @var integer
     */
    const CREATION_AFTER_NOW = 4;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setUpdatedAt
     * method and inform that the given
     * DateTime instance represent a date
     * before the current role creation
     * date.
     *
     * The default value of this constant
     * is an integer set to 5.
     *
     * @var integer
     */
    const UPDATE_BEFORE_CREATION = 5;

    /**
     * A RoleBuilder error type.
     *
     * This constant represent an error
     * registered by se setUpdatedAt
     * method and inform that the given
     * DateTime instance represent a date
     * after the present day and time.
     *
     * The default value of this constant
     * is an integer set to 6.
     *
     * @var integer
     */
    const UPDATE_AFTER_NOW = 6;

    /**
     * The current builder last error.
     *
     * Role builder automaticaly store
     * the last error of his own setting
     * methods. By default, this variable
     * is set to RoleBuilder::NO_ERROR.
     *
     * @see Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder::NO_ERROR
     * @var integer
     */
    protected $lastError;

    /**
     * The current Role instance.
     *
     * Role builder encapsulate a role
     * instance to manage it's methods.
     *
     * @var Role
     */
    protected $role;

    /**
     * The current StackUpdate instance.
     *
     * Role builder encapsule a StackUpdate
     * instance to allow a persistance of the
     * Role state instance before modification.
     *
     * The StackUpdate instance is sent to the
     * persist method of the RoleManager to register
     * it into the database.
     *
     * @see Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager::persist()
     * @var StackUpdate
     */
    protected $stackUpdate;

    /**
     * The parent role manager.
     *
     * Role manager transport his role manager
     * parent to allow logical checking it
     * it's methods.
     *
     * @see Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
     * @var RoleManager
     */
    protected $manager;

    /**
     * RoleBuilder constructor.
     *
     * This constructor musn't need anyone
     * to construct the roleBuilder instance.
     *
     * In the case of none of the arguments
     * are passed to the method call, a new
     * Role instance is created and no instance
     * of StackUpdate are registered. This
     * prevent the persistance of empty data
     * into the stackUpdate table.
     *
     * In other case, if a Role instance is
     * passed to the method calling, the given
     * instance come to be the encapluated
     * instance, and a new StackUpdate
     * instance is created from it.
     *
     * @param RoleManager $roleManager The role manage to use for setter validations.
     * @param Role|null   $role        The role to encapsulate.
     * 
     * @see Cscfa\Bundle\CSManager\CoreBundle\Entity\Role      
     */
    public function __construct(RoleManager $roleManager, $role = null)
    {
        $this->manager = $roleManager;
        $this->lastError = self::NO_ERROR;
        
        if ($role === null) {
            $this->role = new Role();
            $this->stackUpdate = null;
        } else {
            $this->role = $role;
            $this->stackUpdate = new StackUpdate();
            $this->stackUpdate->setPreviousState(serialize($role));
        }
    }

    /**
     * Get the current role id.
     *
     * This method directly return the
     * encapsulate role instance id behind
     * it getId method.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role::getId()
     * @return string
     */
    public function getId()
    {
        return $this->role->getId();
    }

    /**
     * Safety set role name.
     *
     * This method allow a safety setting
     * of the current role name. It will
     * previously check if the role name
     * doesn't already exist and if the
     * given name is valid. It's possible
     * to force the replacement of the
     * name without checking by passing
     * true as second argument.
     *
     * If the checking is activate, this
     * method return a boolean as true on
     * success, or false on fail. If check
     * is deactivate, method will allways
     * return true.
     *
     * @param string  $name  The new name to use.
     * @param boolean $force The force state of the validation.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role::setName()        
     * @return boolean
     */
    public function setName($name, $force = false)
    {
        if ($this->manager->roleExists($name) && $name !== $this->role->getName() && ! $force) {
            $this->lastError = self::DUPLICATE_ROLE_NAME;
            return false;
        }
        
        if (! $this->manager->nameIsValid($name) && ! $force) {
            $this->lastError = self::IN;
            return false;
        }
        
        $this->role->setName($name);
        return true;
    }

    /**
     * Get the role name.
     *
     * This method return the current
     * role name. The default name
     * value is null.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role::getName()
     * @return string
     */
    public function getName()
    {
        return $this->role->getName();
    }

    /**
     * Safety set the role child.
     *
     * This method allow to safety set the role
     * child. It's possible to force the setting
     * validation by passing true as second
     * parameter.
     *
     * The validation check if the given child
     * is an instance of Role or null, if not,
     * return false, otherwise, return true.
     * Finally, check if the new child doesn't
     * create circularReference to another Role
     * and replace the child by the previous if
     * one is made. If the validation is
     * deactivate, this method always return true.
     *
     * @param Role|null $child The new child to use.
     * @param boolean   $force The force state of the setter validation.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role::setChild()        
     * @return boolean
     */
    public function setChild($child, $force = false)
    {
        $lastChild = $this->getChild();
        
        if ($child instanceof Role && $this->manager->roleExists($child) && ! $force) {
            $this->role->setChild($child);
        } else if (! ($child instanceof Role) && $child !== null && ! $force) {
            $this->lastError = self::INVALID_ROLE_INSTANCE_OF;
            return false;
        } else {
            $this->role->setChild($child);
        }
        
        if (! $force && $this->manager->hasCircularReference($this->role)) {
            $this->role->setChild($lastChild);
            $this->lastError = self::CIRCULAR_REFERENCE;
            return false;
        }
        
        return true;
    }

    /**
     * Get the role child.
     *
     * This method return the current role
     * child as Role object or null.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role::getChild()
     * @return Role|null
     */
    public function getChild()
    {
        return $this->role->getChild();
    }

    /**
     * Safety set the role creation date.
     *
     * This method allow to set the current
     * role creation date with a date
     * validation process. It's possible
     * to disable validation by passing true
     * as second argument.
     *
     * The validation check if the given
     * DateTime instance is near the present
     * date.
     *
     * This method return true if the validation
     * success or false if fail. If the
     * validation is deactivated, this method
     * always return true.
     *
     * @param \DateTime $createdAt The new DateTime to use.
     * @param boolean   $force     The force state of the setter validation.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::setCreatedAt()
     * @return boolean
     */
    public function setCreatedAt(\DateTime $createdAt, $force = false)
    {
        if ($force || $createdAt <= new \DateTime()) {
            $this->role->setCreatedAt($createdAt);
            return true;
        } else {
            $this->lastError = self::CREATION_AFTER_NOW;
            return false;
        }
    }

    /**
     * Get the role creation date.
     *
     * This method allow to get the current
     * role creation date as DateTime instance.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::getCreatedAt()
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->role->getCreatedAt();
    }

    /**
     * Safety set the role update date.
     *
     * This method allow to set up the current
     * role last update date. It check if the
     * given date is near the present day and
     * if it's after the role creation date.
     *
     * If the creation date is null, this method
     * will set it up to the given date.
     *
     * It's possible to deactivate the validation
     * by passing true as second argument. In this
     * case, only the update date will be affected.
     *
     * This method return true on validation success
     * or false on failure. If the validation
     * is deactivate, it will always return true.
     *
     * @param \DateTime $updatedAt The new DateTime to ise as update date.
     * @param boolean   $force     The force state of the setter validation.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::setUpdatedAt()        
     * @return boolean
     */
    public function setUpdatedAt(\DateTime $updatedAt, $force = false)
    {
        if ($updatedAt > new \DateTime() && ! $force) {
            $this->lastError = self::UPDATE_AFTER_NOW;
            return false;
        } elseif ($updatedAt < $this->getCreatedAt() && ! $force) {
            $this->lastError = self::UPDATE_BEFORE_CREATION;
            return false;
        } else if ($this->getCreatedAt() === null && ! $force) {
            $this->setCreatedAt($updatedAt);
            $this->role->setUpdatedAt($updatedAt);
        } else {
            $this->role->setUpdatedAt($updatedAt);
        }
        
        return true;
    }

    /**
     * Get the role update date.
     *
     * This method return the current role
     * last update date as DateTime instance.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::getUpdatedAt()
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->role->getUpdatedAt();
    }

    /**
     * Set the role user creator.
     *
     * This method allow to register
     * the role user creator.
     *
     * @param User $createdBy The User instance to use as creator.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::setCreatedBy()      
     * @return RoleBuilder
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->role->setCreatedBy($createdBy);
        return $this;
    }

    /**
     * Get the role user creator.
     *
     * This method allow to get the
     * current role user creator.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::getCreatedBy()
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->role->getCreatedBy();
    }

    /**
     * Set the role user updator.
     *
     * This method allow to register
     * the current role user updator.
     *
     * @param User $updatedBy The User instance to use as updator.
     * 
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::setUpdatedBy()
     * @return RoleBuilder
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->role->setUpdatedBy($updatedBy);
        return $this;
    }

    /**
     * Get the role user updator.
     *
     * This method allow to get the
     * current role user updator.
     *
     * @see    \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject::getUpdatedBy()
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->role->getUpdatedBy();
    }

    /**
     * Get the role StackUpdate.
     *
     * This method allow to get the current
     * role StackUpdate that represent the
     * state of the role before modification.
     *
     * For allow backup and modification trace,
     * this instance is stored into the database
     * and encapsule a serialized state of the
     * role.
     *
     * This instance is stored on construct
     * calling. If the given Role is null at
     * this time, also the StackObject is
     * set to null.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate|null
     */
    public function getStackUpdate()
    {
        return $this->stackUpdate;
    }

    /**
     * Get the role.
     *
     * The RoleBuilder object give abstraction
     * of the Role interface but is not an
     * instance of. It encapsulate a Role
     * instance and provide access to it
     * method with a logic validation.
     *
     * This method allow to get the
     * encapsulate Role instance.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get the last error.
     *
     * This method allow to get the
     * last error state. By default,
     * the last error is set to
     * RoleBuilder::NO_ERROR.
     *
     * @return number
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}
