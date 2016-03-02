<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Event;

use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectBaseEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectNote;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * ProjectNoteEvent class.
 *
 * The ProjectNoteEvent implement
 * the project note event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectNoteEvent extends ProjectBaseEvent {
    
    /**
     * ProjectNoteEvent attribute
     * 
     * This attribute store
     * the event note
     * 
     * @var ProjectNote
     */
    protected $note;
	
	/**
	 * ProjectNoteEvent constructor
	 * 
	 * The ProjectNoteEvent default
	 * constructor
	 * 
	 * @param ProjectNote $note      The event note
	 * @param Project     $project   The event project
	 * @param User        $user      The current user
	 * @param string      $eventName The event name
	 */
	public function __construct(ProjectNote $note = null, Project $project = null, User $user = null, $eventName = null){
	    parent::__construct($project, $user, $eventName);
	    $this->note = $note;
	}
    
    /**
     * Get note
     * 
     * This method return
     * the event note.
     * 
     * @return ProjectNote
     */
    public function getNote() {
        return $this->note;
    }
    
    /**
     * Set note
     * 
     * This method allow to
     * set the event note.
     * 
     * @param ProjectNote $note The event note
     * 
     * @return ProjectNoteEvent
     */
    public function setNote(ProjectNote $note) {
        $this->note = $note;
        return $this;
    }
    
}
