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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectTag;

/**
 * ProjectTagEvent class.
 *
 * The ProjectTagEvent implement
 * the project tag event.
 *
 * @category Event
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectTagEvent extends ProjectBaseEvent {

    /**
     * ProjectTagEvent attribute
     *
     * This attribute store
     * the event tag
     *
     * @var ProjectTag
     */
    protected $tag;
    
    /**
     * Get tag
     *
     * This method return
     * the event tag.
     *
     * @return ProjectTag
     */
    public function getTag() {
        return $this->tag;
    }
    
    /**
     * Set tag
     *
     * This method allow to
     * set the event tag.
     *
     * @param ProjectTag $tag The event tag
     *
     * @return ProjectTagEvent
     */
    public function setTag(ProjectTag $tag) {
        $this->tag = $tag;
        return $this;
    }
    
}
