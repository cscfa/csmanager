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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProjectTag
 *
 * The base ProjectTag entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectTagRepository")
 * @ORM\Table(name="csmanager_project_ProjectTag")
 */
class ProjectTag
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectTag_id", options={"comment":"ProjectTag id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, options={"comment":"The tag name"}, nullable=false, name="csmanager_ProjectTag_name")
     * @Assert\NotBlank()
     */
    protected $name;
    
    /** 
     * @ORM\Column(type="text", options={"comment":"The tag description"}, nullable=false, name="csmanager_ProjectTag_description") 
     */
    protected $description;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="tags")
     */
    protected $project;
    
    /**
     * Project constructor
     * 
     * Setup the entity
     */
    public function __construct()
    {
        $this->project = new ArrayCollection();
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
     * Get name
     * 
     * Return the tag name
     * 
     * @return string - the tag name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * Set the tag name
     * 
     * @param string $name - the tag name
     * 
     * @return ProjectTag - the current instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get description
     * 
     * Return the tag description
     * 
     * @return string - the tag description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * 
     * Set the tag description
     * 
     * @param string $description - the tag description
     * 
     * @return ProjectTag - the current instance
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get project
     * 
     * Return the project
     * 
     * @return ArrayCollection - the project collection
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project
     * 
     * Set the project collection
     * 
     * @param ArrayCollection $project - the project collection
     * 
     * @return ProjectTag - the current instance
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }
 
 
}
