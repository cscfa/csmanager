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

use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\TrackBundle\Event\TrackerEvent;

/**
 * ProjectBaseEvent class.
 *
 * The ProjectBaseEvent implement
 * the project base event.
 *
 * @category Event
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectBaseEvent extends TrackerEvent
{
    /**
     * ProjectBaseEvent attribute.
     *
     * This attribute store
     * the event project.
     *
     * @var Project
     */
    protected $project;

    /**
     * ProjectBaseEvent attribute.
     *
     * This attribute store
     * the event user.
     *
     * @var User
     */
    protected $user;

    /**
     * ProjectBaseEvent constructor.
     *
     * The default ProjectBaseEvent
     * constructor.
     *
     * @param Project $project   The event project
     * @param User    $user      The current user
     * @param string  $eventName The event name
     */
    public function __construct(Project $project = null, User $user = null, $eventName = null)
    {
        parent::__construct($eventName);

        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get project.
     *
     * This method return the
     * event Project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project.
     *
     * This method allow to
     * set the event project.
     *
     * @param Project $project The event Project
     *
     * @return ProjectBaseEvent
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get user.
     *
     * This method return
     * the event User.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * This method allow to
     * set the event user.
     *
     * @param User $user The user
     *
     * @return ProjectBaseEvent
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
