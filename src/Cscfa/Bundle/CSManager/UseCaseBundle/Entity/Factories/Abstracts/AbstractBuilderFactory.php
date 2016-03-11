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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Interfaces\BuilderFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Interfaces\UseCaseEntityFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Interfaces\EntityBuilderInterface;

/**
 * AbstractBuilderFactory.
 *
 * The AbstractBuilderFactory
 * perform the registration
 * of Builder instance.
 *
 * @category EntityFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class AbstractBuilderFactory implements BuilderFactoryInterface, UseCaseEntityFactoryInterface {
    
    /**
     * Builder
     * 
     * The entity builder
     * for the current
     * instance
     * 
     * @var EntityBuilderInterface
     */
    protected $builder;


    /**
     * Get instance
     *
     * This method return an instance
     * of the factory target.
     *
     * @param array $options The creation options
     *
     * @return mixed
     */
    abstract public function getInstance($options);

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
    public function setBuilder(EntityBuilderInterface $builder) {
        $this->builder = $builder;
    }

}