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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;

/**
 * ProjectOwnerEvent class.
 *
 * The ProjectOwnerEvent implement
 * the project owner event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectOwnerEvent extends ProjectBaseEvent {
    
	/**
	 * ProjectOwnerEvent attribute
	 * 
	 * This attribute store
	 * the event owner.
	 * 
	 * @var ProjectOwner
	 */
	protected $owner;
	
	/**
	 * Get owner
	 * 
	 * This method return the
	 * event owner.
	 * 
	 * @return ProjectOwner
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Set owner
	 * 
	 * This method allow to
	 * set the event owner.
	 * 
	 * @param ProjectOwner $owner The event owner
	 * 
	 * @return ProjectOwnerEvent
	 */
	public function setOwner(ProjectOwner $owner) {
		$this->owner = $owner;
		return $this;
	}
}
