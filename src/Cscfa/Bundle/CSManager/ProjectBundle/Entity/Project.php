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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * The base Project entity for the
 * Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectRepository")
 * @ORM\Table(name="csmanager_project_Project",
 *      indexes={@ORM\Index(name="csmanager_project_project_indx", columns={"csmanager_Project_name"})}
 *      )
 * @ORM\HasLifecycleCallbacks
 */
class Project
{

    /**
     * Id
     * 
     * The project id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_Project_id", options={"comment":"Project id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * Created
     * 
     * The entity creation date
     * 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_Project_created", options={"comment":"Project date of creation"}) 
     */
    protected $created;

    /** 
     * Updated
     * 
     * The entity update date
     * 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_Project_updated", options={"comment":"Project date of last update"}) 
     */
    protected $updated;

    /** 
     * Deletate
     * 
     * The entity deletion state
     * 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The Project deletion state"}, nullable=false, name="csmanager_Project_deleted") 
     */
    protected $deleted;

    /** 
     * Name
     * 
     * The project name
     * 
     * @ORM\Column(type="string", length=255, options={"comment":"The Project name"}, nullable=false, unique=true, name="csmanager_Project_name") 
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Project owners
     * 
     * The project referenced owners
     * 
     * @ORM\OneToMany(targetEntity="ProjectOwner", mappedBy="project")
     * @Assert\NotNull()
     */
    protected $projectOwners;

    /**
     * Status
     * 
     * The project status
     * 
     * @ORM\ManyToOne(targetEntity="ProjectStatus")
     * @ORM\JoinColumn(name="csmanager_Project_status_id", referencedColumnName="csmanager_ProjectStatus_id")
     * @Assert\NotNull()
     */
    protected $status;

    /**
     * Tags
     * 
     * The project tags
     * 
     * @ORM\ManyToMany(targetEntity="ProjectTag", inversedBy="project")
     * @ORM\JoinTable(name="tk_csmanager_project_join_tags",
     *      joinColumns={@ORM\JoinColumn(name="csmanager_project_id", referencedColumnName="csmanager_Project_id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="csmanager_tag_id", referencedColumnName="csmanager_ProjectTag_id")}
     *      )
     */
    protected $tags;
    
    /** 
     * Summary
     * 
     * The project short description
     * 
     * @ORM\Column(type="text", options={"comment":"The Project short description"}, nullable=true, name="csmanager_Project_summary") 
     */
    protected $summary;


    /**
     * Notes
     * 
     * The project notes
     * 
     * @ORM\OneToMany(targetEntity="ProjectNote", mappedBy="project")
     */
    protected $notes;
    
    /**
     * Links
     * 
     * The project notes
     * 
     * @ORM\OneToMany(targetEntity="ProjectLink", mappedBy="project")
     */
    protected $links;
    
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
        $this->projectOwners = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Get name
     * 
     * Return the name
     * of the project.
     * 
     * @return string - the project name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get projectOwners
     * 
     * Return the projectOwners
     * of the project.
     * 
     * @return ArrayCollection - the collection of project owners
     */
    public function getProjectOwners()
    {
        return $this->projectOwners;
    }

    /**
     * Get status
     * 
     * Return the status
     * of the project.
     * 
     * @return ProjectStatus - the project status
     */
    public function getStatus()
    {
        return $this->status;
    }
          
    /**
     * Set updated
     * 
     * Setup the updated date
     * to the current date.
     * 
     * @return Project - the current entity
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
     * @return Project - the current entity
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
     * Set name
     * 
     * Set the project
     * name
     * 
     * @param string $name - the project name
     * 
     * @return Project - the current instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set projectOwners
     * 
     * Set the project
     * projectOwners
     * 
     * @param ArrayCollection $projectOwners - the collection of projectOwners
     * 
     * @return Project - the current instance
     */
    public function setProjectOwners($projectOwners)
    {
        $this->projectOwners = $projectOwners;
        return $this;
    }

    /**
     * Set project status
     * 
     * Set the project status
     * 
     * @param ProjectStatus $status - the project status
     * 
     * @return Project - the current instance
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * Get tags
     * 
     * Return the tags
     * of the project.
     * 
     * @return ArrayCollection - the project tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set project tags
     * 
     * Set the project tags
     * 
     * @param ArrayCollection $tags - the project tags
     * 
     * @return Project - the current instance
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get summary
     * 
     * Return the summary
     * of the project.
     * 
     * @return string - the project summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set project summary
     * 
     * Set the project summary
     * 
     * @param string $summary - the project summary
     * 
     * @return Project - the current instance
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get notes
     * 
     * Return the notes
     * of the project.
     * 
     * @return ArrayCollection - the project notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set project notes
     * 
     * Set the project notes
     * 
     * @param ArrayCollection $notes - the project notes
     * 
     * @return Project - the current instance
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Get links
     * 
     * Return the links
     * of the project.
     * 
     * @return ArrayCollection - the project links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set project links
     * 
     * Set the project links
     * 
     * @param ArrayCollection $links - the project links
     * 
     * @return Project - the current instance
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }
 
}