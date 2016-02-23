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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectNote
 *
 * The base ProjectNote entity for the
 * Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectNoteRepository")
 * @ORM\Table(name="csmanager_project_ProjectNote")
 * @ORM\HasLifecycleCallbacks
 */
class ProjectNote
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectNote_id", options={"comment":"ProjectNote id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_ProjectNote_created", options={"comment":"ProjectNote date of creation"}) 
     */
    protected $created;

    /** 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_ProjectNote_updated", options={"comment":"ProjectNote date of last update"}) 
     */
    protected $updated;

    /** 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The ProjectNote deletion state"}, nullable=false, name="csmanager_ProjectNote_deleted") 
     */
    protected $deleted;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="notes")
     * @ORM\JoinColumn(name="csmanager_ProjectNote_project_id", referencedColumnName="csmanager_Project_id")
     */
    protected $project;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\SecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="csmanager_ProjectNote_note_user_id", referencedColumnName="user_id")
     */
    protected $user;
    
    /** 
     * @ORM\Column(type="text", options={"comment":"The ProjectNote content"}, nullable=false, name="csmanager_ProjectNote_description") 
     */
    protected $content;
    
    /**
     * ProjectNote constructor
     * 
     * Setup the entity
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->deleted = false;
        $this->updated = null;
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
     * Set updated
     * 
     * Setup the updated date
     * to the current date.
     * 
     * @return ProjectNote - the current entity
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
     * @return ProjectNote - the current entity
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
     * Set project
     * 
     * Set the referenced
     * project
     * 
     * @param Project $user - the referenced project
     * 
     * @return ProjectNote - the current entity
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
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
     * Set user
     * 
     * Set the referenced
     * user
     * 
     * @param User $user - the referenced user
     * 
     * @return ProjectNote - the current entity
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get content
     * 
     * Return the content.
     * 
     * @return string - the content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     * 
     * Set the content
     * 
     * @param string $content - the content
     * 
     * @return ProjectNote - the current entity
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
 
}
