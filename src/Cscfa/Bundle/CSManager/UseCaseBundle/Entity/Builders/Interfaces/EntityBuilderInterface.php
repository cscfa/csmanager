<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category EntityBuilder
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Interfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * EntityBuilderInterface.
 *
 * The EntityBuilderInterface
 * define entity building methods.
 *
 * @category EntityBuilder
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface EntityBuilderInterface {
    
    /**
     * Set entity
     * 
     * This method allow to set 
     * the entity to build.
     * 
     * @param mixed $entity The entity to build
     * 
     * @return EntityBuilderInterface
     */
    public function setEntity($entity);
    
    /**
     * Get entity
     * 
     * This method return the builded
     * entity.
     * 
     * @return mixed $entity The builded entity
     */
    public function getEntity();
    
    /**
     * Add
     * 
     * This method add a
     * new property to the
     * entity.
     * 
     * @param mixed $property The property to add
     * @param mixed $data     The data to use
     * @param array $options  The building options
     * 
     * @return EntityBuilderInterface
     */
    public function add($property, $data = null, array $options = array());
    
    /**
     * Set process chain
     * 
     * This method allow to set
     * the chain of responsibility
     * that perform the adding action.
     * 
     * @param ChainOfResponsibilityInterface $chain The chain of responsibility
     * 
     * @return EntityBuilderInterface
     */
    public function setProcessChain(ChainOfResponsibilityInterface $chain);
    
    /**
     * Get process chain
     * 
     * This method return the
     * current chain of
     * responsibility.
     * 
     * @return ChainOfResponsibilityInterface
     */
    public function getProcessChain();
    
}