<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Repository
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StackUpdate class.
 *
 * The StackUpdate class is the main stack entity for 
 * database persistance. It precise logical storage 
 * informations.
 *
 * This entity is stored into the csmanager_stack_update table
 * of the database and have an index called cs_manager_stack_update_indx
 * that reference the name of the user updator and the update date to 
 * allow quikly access into finding by name or date case.
 *
 * The repository of this entity is located into
 * the Entity\Repository folder of the core bundle.
 *
 * @category Repository
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\CoreBundle\Entity\Repository\StackUpdateRepository")
 * @ORM\Table(
 *      name="csmanager_stack_update", indexes={@ORM\Index(name="cs_manager_stack_update_indx", columns={"stack_updating_user", "stack_update_date"})}
 * )
 * @ORM\HasLifecycleCallbacks
 */
class StackUpdate
{

    /**
     * The id field.
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into stack_update_id field into 
     * GUID format, is unique and can't be null.
     * 
     * @ORM\Column(
     *      type="guid", name="stack_update_id", unique=true, nullable=false, options={"comment":"updating stack identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * The previousState field.
     * 
     * This field represent a state of an
     * entity into it serialized format.
     * 
     * It is stored ito stack_update_previous_state
     * field and can be null.
     * 
     * @ORM\Column(type="text", name="stack_update_previous_state", unique=false, nullable=true, options={"comment":"Element previous state"})
     */
    protected $previousState;

    /**
     * The update date field.
     * 
     * This field represent the date of
     * update of the current stack entity
     * state.
     * 
     * @ORM\Column(type="datetime", name="stack_update_date", nullable=false, options={"comment":"Stack updating date"})
     */
    protected $date;

    /**
     * The updateBy field.
     * 
     * This field store the user updator.
     * 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="stack_updating_user", referencedColumnName="user_id")
     **/
    protected $updatedBy;

    /**
     * Get the id of the stack.
     *
     * The method getId() allow to get the identity
     * of the current stack, formated into a secured
     * UUID format.
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the previous state.
     *
     * The method getPreviousState() allow to get the 
     * previous state of the stored entity contained
     * into the current stack.
     *
     * @return string
     */
    public function getPreviousState()
    {
        return $this->previousState;
    }

    /**
     * Set the previous state.
     * 
     * This method allow to store a serialized state
     * of an entity to database storage. The goal
     * of this procedure is to allow a backup for
     * each state.
     * 
     * @param string $previousState The serialized entity to store.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
     */
    public function setPreviousState($previousState)
    {
        $this->previousState = $previousState;
        return $this;
    }

    /**
     * Get the date of update.
     * 
     * This method allow to get the
     * update date of the current
     * stack entity state.
     * 
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the date of update.
     * 
     * This method allow to set the
     * update date of the current
     * stack entity state.
     * 
     * @param \DateTime $date The current object state update date.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the updator user.
     * 
     * This method allow to get the
     * current stack entity state
     * user updator.
     * 
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set the User updator.
     * 
     * This method allow to set the
     * current stack entity state
     * user updator.
     * 
     * @param User $updatedBy The current object state user updator.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}
