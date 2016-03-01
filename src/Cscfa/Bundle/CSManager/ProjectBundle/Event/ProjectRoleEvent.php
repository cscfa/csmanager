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

use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectOwnerEvent;

/**
 * ProjectRoleEvent class.
 *
 * The ProjectRoleEvent implement
 * the project role event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectRoleEvent extends ProjectOwnerEvent {

    /**
     * ProjectRoleEvent attribute
     *
     * This attribute store
     * the event role type.
     *
     * @var string
     */
    protected $type;

    /**
     * ProjectRoleEvent attribute
     *
     * This attribute store
     * the event role mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * ProjectRoleEvent attribute
     *
     * This attribute store
     * the event project
     * property.
     *
     * @var string
     */
    protected $property;
	
	/**
	 * Get type
	 * 
	 * This method return the
	 * event type.
	 * 
	 * @return string
	 */
    public function getType() {
        return $this->type;
    }
	
	/**
	 * Set type
	 * 
	 * This method allow to
	 * set the event type.
	 * 
	 * @param string $type The event type
	 * 
	 * @return ProjectRoleEvent
	 */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
	
	/**
	 * Get mode
	 * 
	 * This method return the
	 * event mode.
	 * 
	 * @return string
	 */
    public function getMode() {
        return $this->mode;
    }
	
	/**
	 * Set mode
	 * 
	 * This method allow to
	 * set the event mode.
	 * 
	 * @param string $mode The event mode
	 * 
	 * @return ProjectRoleEvent
	 */
    public function setMode($mode) {
        $this->mode = $mode;
        return $this;
    }
	
	/**
	 * Get property
	 * 
	 * This method return the
	 * event property.
	 * 
	 * @return string
	 */
    public function getProperty() {
        return $this->property;
    }
	
	/**
	 * Set property
	 * 
	 * This method allow to
	 * set the event property.
	 * 
	 * @param string $property The event property
	 * 
	 * @return ProjectRoleEvent
	 */
    public function setProperty($property) {
        $this->property = $property;
        return $this;
    }
 

}
