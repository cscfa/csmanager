<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category EntityFactory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Interfaces;

/**
 * UseCaseEntityFactoryInterface.
 *
 * The UseCaseEntityFactoryInterface
 * define the creation methods of
 * Entity factories.
 *
 * @category EntityFactory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface UseCaseEntityFactoryInterface
{
    /**
     * Get instance.
     *
     * This method return an instance
     * of the factory target.
     *
     * @param array $options The creation options
     *
     * @return mixed
     */
    public function getInstance(array $options = null);
}
