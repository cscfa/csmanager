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
 * ProjectRoleEvent class.
 *
 * The ProjectRoleEvent implement
 * the project role event.
 *
 * @category Event
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectRoleEvent extends ProjectOwnerEvent
{
    /**
     * ProjectRoleEvent attribute.
     *
     * This attribute store
     * the event role type.
     *
     * @var string
     */
    protected $type;

    /**
     * ProjectRoleEvent attribute.
     *
     * This attribute store
     * the event role mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * ProjectRoleEvent attribute.
     *
     * This attribute store
     * the event project
     * property.
     *
     * @var string
     */
    protected $property;

    /**
     * ProjectOwnerEvent constructor.
     *
     * The ProjectOwnerEvent default
     * constructor
     *
     * @param string       $type      The event role type
     * @param string       $mode      The event role mode
     * @param string       $property  The event project property
     * @param ProjectOwner $owner     The event owner
     * @param Project      $project   The event project
     * @param User         $user      The current user
     * @param string       $eventName The event name
     */
    public function __construct(
        $type = null,
        $mode = null,
        $property = null,
        ProjectOwner $owner = null,
        Project $project = null,
        User $user = null,
        $eventName = null
    ) {
        parent::__construct($owner, $project, $user, $eventName);

        $this->type = $type;
        $this->mode = $mode;
        $this->property = $property;
    }

    /**
     * Get type.
     *
     * This method return the
     * event type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * This method allow to
     * set the event type.
     *
     * @param string $type The event type
     *
     * @return ProjectRoleEvent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get mode.
     *
     * This method return the
     * event mode.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set mode.
     *
     * This method allow to
     * set the event mode.
     *
     * @param string $mode The event mode
     *
     * @return ProjectRoleEvent
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get property.
     *
     * This method return the
     * event property.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set property.
     *
     * This method allow to
     * set the event property.
     *
     * @param string $property The event property
     *
     * @return ProjectRoleEvent
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }
}
