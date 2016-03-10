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
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Interfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Interfaces\EntityBuilderInterface;

/**
 * BuilderFactoryInterface.
 *
 * The BuilderFactoryInterface
 * define the creation methods of
 * Entity factories within a builder.
 *
 * @category EntityFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface BuilderFactoryInterface {

    /**
     * Set builder
     *
     * This method allow to
     * set the entity builder.
     *
     * @param EntityBuilderInterface $builder The entity builder
     *
     * @return UseCaseEntityFactoryInterface
     */
    public function setBuilder(EntityBuilderInterface $builder);
}