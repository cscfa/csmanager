<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Entity
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity;

use Cscfa\Bundle\CSManager\UseCaseBundle\Interfaces\TaskCaseInterface;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;
use Cscfa\Bundle\CSManager\UseCaseBundle\Interfaces\TaskStatusInterface;
use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UseCase class.
 *
 * The base UseCase entity for the
 * Cscfa project manager
 *
 * @category Entity
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * 
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Repository\UseCaseRepository")
 * @ORM\Table(name="csmanager_usecase_UseCase",
 *      indexes={@ORM\Index(name="csmanager_usecase_UseCase_indx", columns={"csmanager_UseCase_name"})}
 *      )
 * @ORM\HasLifecycleCallbacks
 */
class UseCase implements TaskCaseInterface {

    /**
     * Id
     * 
     * The UseCase id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_UseCase_id", options={"comment":"UseCase id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;

    /** 
     * Created
     * 
     * The entity creation date
     * 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_UseCase_created", options={"comment":"UseCase date of creation"}) 
     * @var \DateTime
     */
    protected $created;

    /** 
     * Updated
     * 
     * The entity update date
     * 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_UseCase_updated", options={"comment":"UseCase date of last update"}) 
     * @var \DateTime
     */
    protected $updated;

    /**
     * Creator
     *
     * The UseCase creator
     * 
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner")
     * @ORM\JoinColumn(name="csmanager_UseCase_ProjectOwner_id", referencedColumnName="csmanager_ProjectOwner_id")
     * @var ProjectOwner
     */
    protected $creator;
    
    /**
     * Project
     * 
     * The UseCase project
     * 
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(name="csmanager_UseCase_Project_id", referencedColumnName="csmanager_Project_id")
     * @var Project
     */
    protected $project;
    
    /**
     * Status
     * 
     * The UseCase status
     * 
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCaseStatus")
     * @ORM\JoinColumn(name="csmanager_UseCase_Status_id", referencedColumnName="csmanager_UseCaseStatus_id")
     * @var TaskStatusInterface
     */
    protected $status;
    
    /**
     * Tag
     * 
     * The UseCase tags
     * 
     * @ORM\ManyToMany(targetEntity="UseCaseTag")
     * @ORM\JoinTable(name="tk_csmanager_useCase_join_tags",
     *      joinColumns={@ORM\JoinColumn(name="csmanager_UseCase_id", referencedColumnName="csmanager_UseCase_id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="csmanager_UseCaseTag_id", referencedColumnName="csmanager_UseCaseTag_id")}
     *      )
     * @var ArrayCollection
     */
    protected $tag;

    /**
     * Name
     *
     * The UseCase name
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=false, name="csmanager_UseCase_name", options={"comment":"UseCase name"})
     * @var string
     */
    protected $name;
    
    /**
     * Description
     * 
     * The UseCase description
     * 
     * @ORM\Column(type="text", nullable=true, name="csmanager_UseCase_description", options={"comment":"UseCase description"})
     * @var string
     */
    protected $description;

    /**
     * UseCase Childs
     *
     * The UseCase usecase childs
     *
     * @ORM\ManyToMany(targetEntity="UseCase")
     * @ORM\JoinTable(name="tk_csmanager_useCase_join_childs",
     *      joinColumns={@ORM\JoinColumn(name="csmanager_UseCase_id", referencedColumnName="csmanager_UseCase_id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="csmanager_UseCase_id", referencedColumnName="csmanager_UseCase_id")}
     *      )
     * @var ArrayCollection
     */
    protected $useCaseChilds;

    /*
     * TODO: create entity in task bundle
     * @ORM\ManyToMany(targetEntity="Cscfa\Bundle\CSManager\TaskBundle\Entity\Task")
     * @ORM\JoinTable(name="tk_csmanager_useCase_join_task_childs",
     *      joinColumns={@ORM\JoinColumn(name="csmanager_UseCase_id", referencedColumnName="csmanager_UseCase_id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="csmanager_Task_id", referencedColumnName="csmanager_Task_id")}
     *      )
     */
    /**
     * UseCase task Childs
     *
     * The UseCase task childs
     *
     * @var ArrayCollection
     */
    protected $taskChilds;
    
    /**
     * UseCase constructor
     * 
     * The default constructor
     * 
     * @param string              $name          The name [optional]
     * @param string              $description   The description [optional]
     * @param Project             $project       The project [optional]
     * @param ProjectOwner        $creator       The creator [optional]
     * @param TaskStatusInterface $status        The status [optional]
     * @param ArrayCollection     $tag           The tag collection [optional]
     * @param ArrayCollection     $taskChilds    The task childs collection [optional]
     * @param ArrayCollection     $useCaseChilds The UseCase childs collection [optional]
     */
    public function __construct(
            $name = null,                           // The name
            $description = null,                    // The description
            Project $project = null ,               // The project
            ProjectOwner $creator = null,           // The creator
            TaskStatusInterface $status = null,     // The status
            ArrayCollection $tag = null,            // The tag collection
            ArrayCollection $taskChilds = null,     // The task childs collection
            ArrayCollection $useCaseChilds = null   // The UseCase childs collection
    ){
        $this->name = $name;
        $this->description = $description;
        $this->project = $project;
        $this->creator = $creator;
        $this->status = $status;
        $this->tag = $tag;
        $this->taskChilds = $taskChilds;
        $this->useCaseChilds = $useCaseChilds;
        
        $this->created = new \DateTime();
    }
    
    /**
     * Get id
     * 
     * This method return the
     * current entity id.
     * 
     * @return string
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Set id
     * 
     * This method allow
     * to set the current 
     * entity id.
     * 
     * @param string $id The new id
     * 
     * @return UseCase
     */
    private function setId($id){
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get name
     * 
     * This method return the
     * current usecase name.
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set name
     * 
     * This method allow
     * to set the current 
     * usecase name.
     * 
     * @param string $name The new name
     * 
     * @return UseCase
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get creator
     * 
     * This method return the
     * current usecase creator.
     * 
     * @return ProjectOwner
     */
    public function getCreator() {
        return $this->creator;
    }
    
    /**
     * Set creator
     * 
     * This method allow
     * to set the current 
     * usecase creator.
     * 
     * @param ProjectOwner $user The new creator
     * 
     * @return UseCase
     */
    public function setCreator(ProjectOwner $user) {
        $this->creator = $user;
        return $this;
    }
    
    /**
     * Get created
     * 
     * This method return the
     * current usecase created
     * date.
     * 
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Get description
     * 
     * This method return the
     * current usecase description.
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set description
     * 
     * This method allow
     * to set the current 
     * usecase description.
     * 
     * @param string $description The new description
     * 
     * @return UseCase
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get status
     * 
     * This method return the
     * current usecase status.
     * 
     * @return TaskStatusInterface
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set status
     * 
     * This method allow
     * to set the current 
     * usecase status.
     * 
     * @param TaskStatusInterface $status The new status
     * 
     * @return UseCase
     */
    public function setStatus(TaskStatusInterface $status) {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get updated
     * 
     * This method return the
     * current usecase updated
     * date.
     * 
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }
    
    /**
     * Update
     * 
     * This method allow
     * to reset the current 
     * usecase update date.
     * 
     * @ORM\PreUpdate
     * @return UseCase
     */
    public function update() {
        $this->updated = new \DateTime();
        return $this;
    }
    
    /**
     * Get project
     * 
     * This method return the
     * current usecase project.
     * 
     * @return Project
     */
    public function getProject() {
        return $this->project;
    }
    
    /**
     * Set project
     * 
     * This method allow
     * to set the current 
     * usecase project.
     * 
     * @param Project $project The new project
     * 
     * @return UseCase
     */
    public function setProject(Project $project) {
        $this->project = $project;
        return $this;
    }
     
    /**
     * Get Tags
     * 
     * This method return the
     * current usecase Tags.
     * 
     * @return ArrayCollection
     */
    public function getTags() {
        return $this->tag;
    }
    
    /**
     * Set tags
     * 
     * This method allow
     * to set the current 
     * usecase tags.
     * 
     * @param ArrayCollection $tags The new tag collection
     * 
     * @return UseCase
     */
    public function setTags(ArrayCollection $tag) {
        $this->tag = $tag;
        return $this;
    }
     
    /**
     * Get childs
     * 
     * This method return the
     * current usecase childs.
     * 
     * @return ArrayCollection
     */
    public function getChilds(){
        return $this->useCaseChilds;
    }
    
    /**
     * Set childs
     * 
     * This method allow
     * to set the current 
     * usecase childs.
     * 
     * @param ArrayCollection $childs The new childs collection
     * 
     * @return UseCase
     */
    public function setChilds(ArrayCollection $childs){
        $this->useCaseChilds = $childs;
        return $this;
    }
    
    /**
     * Get tasks
     * 
     * This method return the
     * current usecase tasks.
     * 
     * @return ArrayCollection
     */
    public function getTasks(){
        return $this->taskChilds;
    }
    
    /**
     * Set tasks
     * 
     * This method allow
     * to set the current 
     * usecase tasks.
     * 
     * @param ArrayCollection $tasks The new tasks collection
     * 
     * @return UseCase
     */
    public function setTasks(ArrayCollection $tasks){
        $this->taskChilds = $tasks;
        return $this;
    }

}
