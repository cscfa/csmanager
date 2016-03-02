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
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\TrackBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * TrackerEvent class.
 *
 * The TrackerEvent implement
 * the methods of tracked event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class TrackerEvent extends Event {

    /**
     * TrackerEvent attribute
     *
     * This attribute store
     * the event name.
     *
     * @var string
     */
    protected $eventName;
    
    /**
     * TrackerEvent constructor
     * 
     * The default TrackerEvent
     * constructor.
     * 
     * @param string $eventName
     */
    public function __construct($eventName = null){
        $this->eventName = $eventName;
    }
	
	/**
	 * Get event name
	 * 
	 * This method return
	 * the event name.
	 * 
	 * @return string
	 */
    public function getEventName() {
        return $this->eventName;
    }
	
	/**
	 * Set event name
	 * 
	 * This method allow to
	 * set the event name.
	 * 
	 * @param string $eventName The event name
	 * 
	 * @return TrackerEvent
	 */
    public function setEventName($eventName) {
        $this->eventName = $eventName;
        return $this;
    }
    
}
