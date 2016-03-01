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
