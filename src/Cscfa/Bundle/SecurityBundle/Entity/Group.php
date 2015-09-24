<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group class.
 *
 * The Group class allow to precise a
 * group of user and bellow that, a role
 * collection.
 *
 * The repository of this entity is located into
 * the Entity\Repository folder of the core bundle.
 *
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\SecurityBundle\Entity\Repository\GroupRepository")
 * @ORM\Table(name="csmanager_core_group",
 *      indexes={@ORM\Index(name="cs_manager_group_indx", columns={"group_name_canonical"})}
 *      )
 */
class Group extends StackableObject
{

    /**
     * The id field
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into group_id field into GUID
     * format, is unique and can't be null.
     *
     * @ORM\Column(
     *      type="guid", name="group_id", unique=true, nullable=false, options={"comment":"group identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * The group name.
     * 
     * The name of the group
     * stored as string into
     * the database.
     * 
     * @ORM\Column(
     *      type="string", length=255, nullable=false, name="group_name", options={"comment":"group name"}
     * )
     */
    protected $name;

    /**
     * The group canonical name.
     * 
     * The canonical name
     * of the group. This name
     * must be the same of the
     * group name but in lower
     * case.
     * 
     * @ORM\Column(
     *      type="string", length=255, unique=true, nullable=false, name="group_name_canonical", options={"comment":"canonical group name logical coherency with the group name in lower case"}
     * )
     */
    protected $nameCanonical;

    /**
     * The roles.
     * 
     * A group of role that the
     * current group is owners.
     * 
     * @var Collection
     * 
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="tk_csmanager_group_join_role",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="group_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="role_id")}
     *      )
     */
    protected $roles;

    /**
     * The expired state.
     * 
     * The expired state of the
     * group.
     * 
     * @ORM\Column(
     *      type="boolean", name="group_expired", nullable=true, options={"comment":"the expired state of the group"}
     * )
     */
    protected $expired;

    /**
     * The expiration date.
     * 
     * The date of the epiration
     * of the current group.
     * 
     * @ORM\Column(
     *      type="datetime", name="group_expire_at", nullable=true, options={"comment":"the expiration date of the group"}
     * )
     */
    protected $expiresAt;

    /**
     * The locked state.
     *
     * The locked state of the
     * group.
     *
     * @ORM\Column(
     *      type="boolean", name="group_locked", nullable=true, options={"comment":"the locked state of the group"}
     * )
     */
    protected $locked;

    /**
     * Default constructor.
     * 
     * This method is the
     * default Group constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->locked = false;
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
        return $this->id;
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
        return $this->name;
    }

    /**
     * Set the name.
     * 
     * This method allow to set the
     * froup name.
     * 
     * @param string $name The new group name
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
        return $this->nameCanonical;
    }

    /**
     * Set the canonical name.
     * 
     * This method allow to
     * set the canonical group
     * name.
     * 
     * By convention, the canonical
     * name is the lower case name
     * of the group.
     * 
     * @param string $nameCanonical The canonical name
     * 
     * @return string
     */
    public function setNameCanonical($nameCanonical)
    {
        $this->nameCanonical = $nameCanonical;
        return $this;
    }

    /**
     * Add a Role.
     * 
     * This method allow to
     * add a Role to the current
     * group.
     * 
     * @param Role $role The role instance to add
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function addRole(Role $role)
    {
        if ($this->hasRole($role)) {
            return $this;
        } else {
            $this->roles->add($role);
        }
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
        if ($role instanceof Role) {
            $role = $role->getName();
        } else {
            return false;
        }
        
        if ($this->roles === null || $this->roles->count() == 0) {
            return false;
        }
        
        foreach ($this->roles as $roles) {
            if ($roles->getName() == $role) {
                return true;
            }
        }
        
        return false;
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
        return $this->roles;
    }

    /**
     * Set the Role.
     * 
     * This method allow to set the
     * complete Role collection of
     * the current group.
     * 
     * @param Collection $roles The Role collection
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
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
        if ($this->expiresAt === null) {
            return false;
        }
        
        if ((new \DateTime())->getTimestamp() >= $this->expiresAt->getTimestamp()) {
            $this->setExpired(true);
        }
        
        return $this->expired;
    }

    /**
     * Set expired.
     * 
     * This method allow to
     * set the expiration state
     * of the current group.
     * 
     * @param boolean $expired The expiration state
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
        return $this;
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
        return $this->expiresAt;
    }

    /**
     * Set the expiration time.
     * 
     * This method allow to
     * set the expiration time
     * of the current group.
     * 
     * @param \DateTime $expiresAt The expiration time
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\Group
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
        return $this;
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
        return $this->locked;
    }

    /**
     * Set locked state.
     * 
     * This method allow to
     * set the current locked 
     * state of the group.
     * 
     * @param boolean $locked The locked state
     * 
     * @return Group
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * The to string method.
     *
     * This method return the
     * current instance getName
     * result.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
} 
