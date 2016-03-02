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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectLink;
use Cscfa\Bundle\CSManager\TrackBundle\Event\TrackerEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * ProjectLinkEvent class.
 *
 * The ProjectLinkEvent implement
 * the project link event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectLinkEvent extends ProjectBaseEvent {
	
	/**
	 * ProjectLinkEvent attribute
	 * 
	 * This attribute store
	 * the event link.
	 * 
	 * @var ProjectLink
	 */
	protected $link;
	
	/**
	 * ProjectLinkEvent constructor
	 * 
	 * The ProjectLinkEvent default
	 * constructor
	 * 
	 * @param ProjectLink $link      The event link
	 * @param Project     $project   The event project
	 * @param User        $user      The current user
	 * @param string      $eventName The event name
	 */
	public function __construct(ProjectLink $link = null, Project $project = null, User $user = null, $eventName = null){
	    parent::__construct($project, $user, $eventName);
	    $this->link = $link;
	}
	
	/**
	 * Get link
	 * 
	 * This method return
	 * the event link
	 * 
	 * @return ProjectLink
	 */
	public function getLink() {
		return $this->link;
	}
	
	/**
	 * Set link
	 * 
	 * This method allow
	 * to set the event
	 * link
	 * 
	 * @param ProjectLink $link The event link
	 * 
	 * @return ProjectLinkEvent
	 */
	public function setLink(ProjectLink $link) {
		$this->link = $link;
		return $this;
	}
	
}
