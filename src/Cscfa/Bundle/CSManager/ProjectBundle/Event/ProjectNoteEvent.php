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
