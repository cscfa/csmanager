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
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Entity;

use Cscfa\Bundle\SecurityBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectOwner
 *
 * The base ProjectOwner entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectOwnerRepository")
 * @ORM\Table(name="csmanager_project_ProjectOwner")
 */
class ProjectOwner
{

    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectOwner_id", options={"comment":"ProjectOwner id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_ProjectOwner_created", options={"comment":"ProjectOwner date of creation"}) 
     */
    protected $created;

    /** 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_ProjectOwner_updated", options={"comment":"ProjectOwner date of last update"}) 
     */
    protected $updated;

    /** 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The ProjectOwner deletion state"}, nullable=false, name="csmanager_ProjectOwner_deleted") 
     */
    protected $deleted;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\SecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="csmanager_ProjectOwner_user_id", referencedColumnName="user_id")
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectOwners")
     * @ORM\JoinColumn(name="csmanager_ProjectOwner_project_id", referencedColumnName="csmanager_Project_id")
     */
    protected $project;
    
    /**
     * @ORM\ManyToMany(targetEntity="ProjectRole")
     * @ORM\JoinTable(name="tk_csmanager_ProjectOwner_projectRole",
     *      joinColumns={@ORM\JoinColumn(name="projectowner_id", referencedColumnName="csmanager_ProjectOwner_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="projectrole_id", referencedColumnName="csmanager_ProjectRole_id")}
     *      )
     */
    protected $roles;
    
    /**
     * Project constructor
     * 
     * Setup the entity
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->deleted = false;
        $this->updated = null;
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     * 
     * Return the entity id.
     * 
     * @return string - return the entity id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get created
     * 
     * Return the creation date
     * of the entity.
     * 
     * @return \DateTime - The creation date
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get updated
     * 
     * Return the update date
     * of the entity.
     * 
     * @return \DateTime | null - The entity update date or null if never updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get deleted
     * 
     * Return the deletion state
     * of the entity.
     * 
     * @return boolean - the entity deletion state
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get user
     * 
     * Return the referenced
     * user.
     * 
     * @return User - the referenced user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get project
     * 
     * Return the referenced
     * project.
     * 
     * @return Project - the referenced project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get roles
     * 
     * Return the referenced
     * roles.
     * 
     * @return ArrayCollection - the referenced roles collection
     */
    public function getRoles()
    {
        return $this->roles;
    }
         
    /**
     * Set updated
     * 
     * Setup the updated date
     * to the current date.
     * 
     * @return ProjectOwner - the current entity
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();
        return $this;
    }

    /**
     * Set deleted
     * 
     * Set the deleted state of
     * the entity. If the given
     * state is not a boolean,
     * the variable is cast to 
     * boolean.
     * 
     * @param mixed $deleted - the state of the deletion
     * 
     * @return ProjectOwner - the current entity
     */
    public function setDeleted($deleted)
    {
        if (! is_bool($deleted)) {
            $deleted = boolval($deleted);
        }
        
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * Set user
     * 
     * Set the referenced
     * user
     * 
     * @param User $user - the referenced user
     * 
     * @return ProjectOwner - the current entity
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set project
     * 
     * Set the referenced
     * project
     * 
     * @param Project $project - the referenced project
     * 
     * @return ProjectOwner - the current entity
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Set roles
     * 
     * Set the referenced
     * roles
     * 
     * @param ArrayCollection $roles - the referenced roles
     * 
     * @return ProjectOwner - the current entity
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }
     
    /**
     * Update
     * 
     * PreUpdate the entity to
     * store the update date
     * 
     * @ORM\PreUpdate
     * 
     * @return null
     */
    protected function update(){
        $this->setUpdated();
    }
}