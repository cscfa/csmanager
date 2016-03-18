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

/**
 * TaskStatusInterface interface.
 *
 * The TaskStatusInterface interface define
 * the the UseCase / Task status interface.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface TaskStatusInterface
{
    /**
     * Get name.
     *
     * This method return the
     * status name.
     *
     * @return string
     */
    public function getName();
    /**
     * Set name.
     *
     * This method allow to set
     * the status name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get description.
     *
     * This method return the
     * status description.
     *
     * @return string
     */
    public function getDescription();
    /**
     * Set description.
     *
     * This method allow to set
     * the status description.
     *
     * @param string $description
     */
    public function setDescription($description);
}
