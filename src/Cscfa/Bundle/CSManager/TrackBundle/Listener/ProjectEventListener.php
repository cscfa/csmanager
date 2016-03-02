<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Listener
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\TrackBundle\Listener;

use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectBaseEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectOwnerEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectRoleEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectLinkEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectNoteEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectTagEvent;

/**
 * ProjectEventListener class
 * 
 * Thist class implements
 * event listeners for the
 * project context
 *
 * @category Listener
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 */
class ProjectEventListener extends TrackerListener {
    
    /**
     * On created
     * 
     * This method persist data
     * tracking for a project 
     * creation event
     * 
     * @param ProjectBaseEvent $event The event
     */
    public function onCreated(ProjectBaseEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' created the project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * On remove
     * 
     * This method persist data
     * tracking for a project 
     * remove event
     * 
     * @param ProjectBaseEvent $event The event
     */
    public function onRemove(ProjectBaseEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' removed the project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Add owner
     * 
     * This method persist data
     * tracking for a project 
     * add owner event
     * 
     * @param ProjectOwnerEvent $event The event
     */
    public function onAddOwner(ProjectOwnerEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' add user '%s' as owner in project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            $event->getOwner()->getUser()->getUsername(),
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getOwner()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Update role
     * 
     * This method persist data
     * tracking for a project 
     * owner role update event
     * 
     * @param ProjectRoleEvent $event The event
     */
    public function onRoleUpdate(ProjectRoleEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' update the user called '%s' roles in project named '%s' on %s. Update [%s] for %s to %s", 
            $event->getUser()->getUsername(), 
            $event->getOwner()->getUser()->getUsername(),
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s"),
            implode(':', $event->getProperty()),
            $event->getType(),
            $event->getMode()
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getOwner()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Add link
     * 
     * This method persist data
     * tracking for a project 
     * adding link event
     * 
     * @param ProjectLinkEvent $event The event
     */
    public function onAddLink(ProjectLinkEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' add link '%s' to project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            $event->getLink()->getLink(),
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getLink()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Remove link
     * 
     * This method persist data
     * tracking for a project 
     * removing link event
     * 
     * @param ProjectLinkEvent $event The event
     */
    public function onRemoveLink(ProjectLinkEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' remove link '%s' from project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            $event->getLink()->getLink(),
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getLink()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Add note
     * 
     * This method persist data
     * tracking for a project 
     * adding note event
     * 
     * @param ProjectNoteEvent $event The event
     */
    public function onAddNote(ProjectNoteEvent $event){
        $date = new \DateTime();
        $content = $event->getNote()->getContent();
        
        $message = sprintf(
            "The user '%s' add note '%s' to project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            strlen($content)>20?substr($content, 0, 20)."...":$content,
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getNote()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Edit note
     * 
     * This method persist data
     * tracking for a project 
     * editing note event
     * 
     * @param ProjectNoteEvent $event The event
     */
    public function onEditNote(ProjectNoteEvent $event){
        $date = new \DateTime();
        $content = $event->getNote()->getContent();
        
        $message = sprintf(
            "The user '%s' edit note '%s' for project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            strlen($content)>20?substr($content, 0, 20)."...":$content,
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getNote()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Remove note
     * 
     * This method persist data
     * tracking for a project 
     * removing note event
     * 
     * @param ProjectNoteEvent $event The event
     */
    public function onRemoveNote(ProjectNoteEvent $event){
        $date = new \DateTime();
        $content = $event->getNote()->getContent();
        
        $message = sprintf(
            "The user '%s' remove note '%s' from project named '%s' on %s", 
            $event->getUser()->getUsername(), 
            strlen($content)>50?substr($content, 0, 50)."...":$content,
            $event->getProject()->getName(), 
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getNote()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Update name
     * 
     * This method persist data
     * tracking for a project 
     * updating name event
     * 
     * @param ProjectBaseEvent $event The event
     */
    public function onUpdateName(ProjectBaseEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' update a project to '%s' on %s", 
            $event->getUser()->getUsername(),
            $event->getProject()->getName(),
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Update summary
     * 
     * This method persist data
     * tracking for a project 
     * updating summary event
     * 
     * @param ProjectBaseEvent $event The event
     */
    public function onSummaryUpdate(ProjectBaseEvent $event){
        $date = new \DateTime();
        $content = $event->getProject()->getSummary();
        
        $message = sprintf(
            "The user '%s' update summary to '%s' for project '%s' on %s", 
            $event->getUser()->getUsername(),
            strlen($content)>20?substr($content, 0, 20)."...":$content,
            $event->getProject()->getName(),
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Update status
     * 
     * This method persist data
     * tracking for a project 
     * updating status event
     * 
     * @param ProjectBaseEvent $event The event
     */
    public function onStatusUpdate(ProjectBaseEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' update status to '%s' for project '%s' on %s", 
            $event->getUser()->getUsername(),
            $event->getProject()->getStatus()->getName(),
            $event->getProject()->getName(),
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Assign tag
     * 
     * This method persist data
     * tracking for a project 
     * assigning tag event
     * 
     * @param ProjectTagEvent $event The event
     */
    public function onAssignTag(ProjectTagEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' assign tag '%s' to project '%s' on %s", 
            $event->getUser()->getUsername(),
            $event->getTag()->getName(),
            $event->getProject()->getName(),
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getTag()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
    /**
     * Remove tag
     * 
     * This method persist data
     * tracking for a project 
     * removing tag event
     * 
     * @param ProjectTagEvent $event The event
     */
    public function onRemoveTag(ProjectTagEvent $event){
        $date = new \DateTime();
        
        $message = sprintf(
            "The user '%s' remove tag '%s' from project '%s' on %s", 
            $event->getUser()->getUsername(),
            $event->getTag()->getName(),
            $event->getProject()->getName(),
            $date->format("Y-m-d H:i:s")
        );
        
        $links = $this->createArrayCollection([$event->getUser(), $event->getProject(), $event->getTag()]);
        $this->getNewTracker($event->getEventName(), $message, $event->getUser(), $links);
        
        $this->doctrine->getManager()->flush();
    }
    
}
