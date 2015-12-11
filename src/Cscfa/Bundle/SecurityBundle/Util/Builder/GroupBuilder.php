<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Builder
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Util\Builder;

use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\SecurityBundle\Entity\Group;
use Cscfa\Bundle\SecurityBundle\Entity\StackUpdate;
use Cscfa\Bundle\SecurityBundle\Entity\Role;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;

/**
 * GroupBuilder class.
 *
 * The GroupBuilder class purpose feater to
 * manage a Group entity with a StackUpdate
 * object into the database behind the
 * GroupManager usage.
 *
 * @category Builder
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.1
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Group
 * @see      Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager
 */
class GroupBuilder implements ErrorRegisteryInterface
{

    /**
     * An error type.
     * 
     * This error represent that
     * a given name is unavailable
     * for a group name usage.
     * 
     * @var integer
     */
    const INVALID_NAME = 1;

    /**
     * An error type.
     * 
     * This error represent that
     * a given name is already in
     * use for a group.
     * 
     * @var integer
     */
    const EXISTING_NAME = 2;

    /**
     * An error type.
     * 
     * This error represent that
     * a given parameter asserted
     * as boolean is of an other
     * type.
     * 
     * @var integer
     */
    const NOT_BOOLEAN = 3;

    /**
     * An error type.
     * 
     * This error represent that
     * a given date asserted as
     * later than current date
     * is before now.
     * 
     * @var integer
     */
    const DATE_BEFORE_NOW = 4;

    /**
     * An error type.
     * 
     * This error represent that
     * a given Role instance does
     * not exist into the database.
     * 
     * @var integer
     */
    const UNEXISTING_ROLE = 5;

    /**
     * An error type.
     * 
     * This error represent that
     * a given Role instance already
     * exist into the group role
     * collection.
     * 
     * @var integer
     */
    const HAS_ALREADY_ROLE = 6;

    /**
     * An error type.
     * 
     * This error represent that
     * a given Role instance does
     * not exist into the Role
     * collection.
     * 
     * @var integer
     */
    const NOT_ROLE_OWNER = 7;

    /**
     * The current builder last error.
     *
     * Group builder automaticaly store
     * the last error of his own setting
     * methods. By default, this variable
     * is set to GroupBuilder::NO_ERROR.
     *
     * @see Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder::NO_ERROR
     * @var integer
     */
    protected $lastError;

    /**
     * The Group instance.
     * 
     * The group builder encapsulate
     * a Group instance to work with.
     * 
     * @var Group
     */
    protected $group;

    /**
     * The current StackUpdate instance.
     *
     * Group builder encapsule a StackUpdate
     * instance to allow a persistance of the
     * Group state instance before modification.
     *
     * The StackUpdate instance is sent to the
     * persist method of the GroupManager to register
     * it into the database.
     *
     * @see Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager::persist()
     * @var StackUpdate
     */
    protected $stackUpdate;

    /**
     * The parent group manager.
     *
     * Group builder transport his group manager
     * parent to allow logical checking it
     * it's methods.
     *
     * @see Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager
     * @var GroupManager
     */
    protected $manager;

    /**
     * Default constructor.
     * 
     * This is the default constructor
     * of the Group builder. It will
     * store a state of the given Group
     * instance before working with or
     * create a new instance to work with
     * if none is given.
     * 
     * @param GroupManager $manager The group manager that check logical validity
     * @param Group        $group   The group instance
     */
    public function __construct(GroupManager $manager, Group $group = null)
    {
        $this->manager = $manager;
        $this->lastError = self::NO_ERROR;
        
        if ($group !== null) {
            $this->group = $group;
            $this->stackUpdate = new StackUpdate();
            $this->stackUpdate->setPreviousState(serialize($group));
            $this->stackUpdate->setDate(new \DateTime());
        } else {
            $this->group = new Group();
            $this->stackUpdate = null;
        }
    }

    /**
     * Get the Group.
     * 
     * This method allow to get the
     * Group instance that is encapsulate
     * into the GroupBuilder.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get the stackUpdate.
     * 
     * This method allow to get the
     * stackUpdate instance that is 
     * encapsulate into the GroupBuilder.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\StackUpdate
     */
    public function getStackUpdate()
    {
        return $this->stackUpdate;
    }

    /**
     * Set the name.
     * 
     * This method allow to set up
     * the group name. It will first 
     * check if the given name is 
     * valid and doesn't already exist 
     * for an other group. If one of
     * this validation fail, the method
     * will return false. However, it
     * will return true.
     * 
     * It's possible to desable this
     * validation by passing true as
     * second parameter. In this case,
     * the method will allways return
     * true.
     * 
     * @param string  $name  The nae to set
     * @param boolean $force The validation force state
     * 
     * @return boolean
     */
    public function setName($name, $force = false)
    {
        if (! $this->manager->nameIsValid($name) && ! $force) {
            $this->lastError = self::INVALID_NAME;
            return false;
        } else if ($this->manager->nameExist(strtolower($name)) && $this->group->getName() != $name && ! $force) {
            $this->lastError = self::EXISTING_NAME;
            return false;
        } else {
            $this->group->setName($name);
            $this->group->setNameCanonical(strtolower($name));
        }
        
        return true;
    }

    /**
     * Set locked state.
     * 
     * This method allow to set the
     * locked state of the group. It
     * will first validate that the
     * given parameter is typed as
     * boolean. If this validation
     * fail the method will return
     * false.
     * 
     * It's possible to desable this
     * validation by passing true as
     * second parameter. In this case,
     * the method will allways return
     * true.
     * 
     * @param boolean $boolean the locked state
     * @param boolean $force   the validation force state
     * 
     * @return boolean
     */
    public function setLocked($boolean, $force = false)
    {
        if ($boolean === true || $boolean === false) {
            $this->group->setLocked($boolean);
            return true;
        } else if ($force) {
            $this->group->setLocked((boolean) $boolean);
            return true;
        }
        
        $this->lastError = self::NOT_BOOLEAN;
        return false;
    }

    /**
     * Set expiration date.
     * 
     * This method allow to set the
     * expiration date of the current
     * group. It will validate that the
     * given date is not before the
     * current date and time. If this
     * validation fail, the method will
     * return false.
     * 
     * It's possible to desable this
     * validation by passing true as
     * second parameter. In this case,
     * the method will allways return
     * true.
     * 
     * @param \DateTime $date  The expiration date
     * @param string    $force The validation force state
     * 
     * @return boolean
     */
    public function setExpiresAt(\DateTime $date = null, $force = false)
    {
        if ($date === null) {
            $this->group->setExpiresAt(null);
            $this->group->setExpired(false);
        } else {
            if (! $force && $date->getTimestamp() < (new \DateTime())->getTimestamp()) {
                $this->lastError = self::DATE_BEFORE_NOW;
                return false;
            }
            
            $this->group->setExpiresAt($date);
            if ($date->getTimestamp() <= (new \DateTime())->getTimestamp()) {
                $this->group->setExpired(true);
            } else {
                $this->group->setExpired(false);
            }
        }
        
        return true;
    }

    /**
     * Add role.
     * 
     * This method allow to add
     * a Role instance to the current
     * Group instance. It will validate
     * that the role exist into the
     * database and the role doesn't
     * already owned by the group. If
     * one of this validation fail, the
     * method will return false.
     * 
     * It's possible to desable this
     * validation by passing true as
     * second parameter. In this case,
     * the method will allways return
     * true.
     * 
     * @param Role   $role  The Role instance to add
     * @param string $force The validation force state
     * 
     * @return boolean
     */
    public function addRole(Role $role, $force = false)
    {
        $roleManager = $this->manager->getRoleManager();
        
        if (! $force && ! $roleManager->roleExists($role->getName())) {
            $this->lastError = self::UNEXISTING_ROLE;
            return false;
        } else if (! $force && $this->group->hasRole($role)) {
            $this->lastError = self::HAS_ALREADY_ROLE;
            return false;
        }
        
        $this->group->addRole($role);
        return true;
    }

    /**
     * Remove a role.
     * 
     * This method allow to remove
     * a role from the current group
     * Role collection. If one of this 
     * validation fail, the method will 
     * return false.
     * 
     * It's possible to desable this
     * validation by passing true as
     * second parameter. In this case,
     * the method will allways return
     * true.
     * 
     * @param Role   $role  The Role instance to remove
     * @param string $force The validation force state
     * 
     * @return boolean
     */
    public function removeRole(Role $role, $force = false)
    {
        if ($this->group->hasRole($role) || $force) {
            $roles = $this->group->getRoles();
            
            foreach ($roles as $key => $value) {
                if ($value === $role) {
                    unset($roles[$key]);
                    break;
                }
            }
            
            $this->group->setRoles($roles);
            
            return true;
        } else {
            $this->lastError = self::NOT_ROLE_OWNER;
            return false;
        }
    }

    /**
     * Get locked state.
     * 
     * This method allow to
     * get the current locked
     * state of the group.
     * 
     * @return boolean
     */
    public function getLocked()
    {
        return $this->group->getLocked();
    }

    /**
     * Get the expiration time.
     * 
     * This method allow to get the
     * expiration time of the current 
     * group.
     * 
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->group->getExpiresAt();
    }

    /**
     * Check if the group has expired.
     * 
     * This method allow to
     * check if the current
     * group is expired.
     * 
     * @return boolean
     */
    public function isExpired()
    {
        return $this->group->isExpired();
    }

    /**
     * Get Roles.
     * 
     * This method allow to get
     * the complete Role collection
     * of the current group.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Collection
     */
    public function getRoles()
    {
        return $this->group->getRoles();
    }

    /**
     * Check if role owned.
     * 
     * This method allow to check
     * if the current group is the
     * owner of a given role.
     * 
     * The role parameter can be
     * a Role instance or a Role
     * name as string.
     * 
     * @param Role|string $role The role to check for
     * 
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->group->hasRole($role);
    }

    /**
     * Get the canonical name.
     * 
     * This method allow to
     * get the canonical group
     * name.
     * 
     * By convention, the canonical
     * name is the lower case name
     * of the group.
     * 
     * @return string
     */
    public function getNameCanonical()
    {
        return $this->group->getNameCanonical();
    }

    /**
     * Get the name.
     * 
     * This method allow to
     * get the name of the
     * group as string.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->group->getName();
    }

    /**
     * Get the id.
     * 
     * This method allow to
     * get the group id as 
     * string.
     * 
     * @return string
     */
    public function getId()
    {
        return $this->group->getId();
    }

    /**
     * Get the last error.
     *
     * This method allow to get the
     * last error state. By default,
     * the last error is set to
     * GroupBuilder::NO_ERROR.
     *
     * @return number
     */
    public function getLastError()
    {
        return $this->lastError;
    }
    
    /**
     * Remove the last error.
     * 
     * This method allow to remove the
     * last error state.
     * 
     * @return void
     */
    public function removeLastError() {
        $this->lastError = self::NO_ERROR;
    }

}
