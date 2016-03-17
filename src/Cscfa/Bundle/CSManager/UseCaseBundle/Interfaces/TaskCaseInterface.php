<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Interfaces;

use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;

/**
 * TaskCaseInterface class.
 *
 * The TaskCaseInterface class define
 * the the UseCase / Task interface.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface TaskCaseInterface
{
    /**
     * Get created.
     *
     * This method return the
     * created date of the
     * task.
     *
     * @return \DateTime
     */
    public function getCreated();

    /**
     * Get updated.
     *
     * This method return the
     * updated date of the
     * task.
     *
     * @return \DateTime
     */
    public function getUpdated();

    /**
     * Get creator.
     *
     * This method return the
     * creator user of the
     * task.
     *
     * @return ProjectOwner
     */
    public function getCreator();
    /**
     * Set creator.
     *
     * This method allow to set
     * the creator user of the
     * task.
     *
     * @param ProjectOwner $user The creator
     */
    public function setCreator(ProjectOwner $user);

    /**
     * Get status.
     *
     * This method return the
     * status of the task.
     *
     * @return TaskStatusInterface
     */
    public function getStatus();
    /**
     * Set status.
     *
     * This method allow to set
     * the status of the task.
     *
     * @param TaskStatusInterface $status The status
     */
    public function setStatus(TaskStatusInterface $status);

    /**
     * Set name.
     *
     * This method return the
     * name of the task.
     *
     * @return string
     */
    public function getName();
    /**
     * Set name.
     *
     * This method allow to set
     * the name of the task.
     *
     * @param string $name The task name
     */
    public function setName($name);

    /**
     * Get description.
     *
     * This method return the
     * description of the task.
     *
     * @return string
     */
    public function getDescription();
    /**
     * Set description.
     *
     * This method allow to set
     * the description of the task.
     *
     * @param string $description The description
     */
    public function setDescription($description);

    /**
     * Update.
     *
     * This method perform the
     * dating update of the task.
     */
    public function update();

    /**
     * Get project.
     *
     * This method return the
     * project.
     *
     * @return Project
     */
    public function getProject();

    /**
     * Set project.
     *
     * This method allow to
     * set the project.
     *
     * @param Project $project The project
     */
    public function setProject(Project $project);
}
