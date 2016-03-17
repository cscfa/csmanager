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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\Event;

use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * ProjectOwnerEvent class.
 *
 * The ProjectOwnerEvent implement
 * the project owner event.
 *
 * @category Event
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectOwnerEvent extends ProjectBaseEvent
{
    /**
     * ProjectOwnerEvent attribute.
     *
     * This attribute store
     * the event owner.
     *
     * @var ProjectOwner
     */
    protected $owner;

    /**
     * ProjectOwnerEvent constructor.
     *
     * The ProjectOwnerEvent default
     * constructor
     *
     * @param ProjectOwner $owner     The event owner
     * @param Project      $project   The event project
     * @param User         $user      The current user
     * @param string       $eventName The event name
     */
    public function __construct(
        ProjectOwner $owner = null,
        Project $project = null,
        User $user = null,
        $eventName = null
    ) {
        parent::__construct($project, $user, $eventName);
        $this->owner = $owner;
    }

    /**
     * Get owner.
     *
     * This method return the
     * event owner.
     *
     * @return ProjectOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner.
     *
     * This method allow to
     * set the event owner.
     *
     * @param ProjectOwner $owner The event owner
     *
     * @return ProjectOwnerEvent
     */
    public function setOwner(ProjectOwner $owner)
    {
        $this->owner = $owner;

        return $this;
    }
}
