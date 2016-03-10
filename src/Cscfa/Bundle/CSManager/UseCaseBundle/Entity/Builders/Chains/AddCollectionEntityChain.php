<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category ChainOfResponsibility
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddCollectionChain;
use Doctrine\ORM\EntityRepository;

/**
 * AddCollectionChain.
 *
 * The AddCollectionChain process setting of
 * "specified property" property for
 * array or object.
 *
 * Process "specified property" action.
 *
 * Store in named key for array and try
 * get{ucfirst("specified property")}()->add()
 * method, or public property "specified
 * property"->add() before passing responsibility.
 *
 * @category ChainOfResponsibility
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class AddCollectionEntityChain extends AddCollectionChain {
    
    /**
     * PropertyClass
     * 
     * The property class
     * 
     * @var string
     */
    protected $propertyClass;
    
    /**
     * Entity repository
     * 
     * The entity repository
     * 
     * @var EntityRepository
     */
    protected $entityRepository;
    
    /**
     * Set property class
     * 
     * This method allow to set the 
     * property class to use.
     * 
     * @param string $propertyClass The property class
     * 
     * @return AddCollectionEntityChain
     */
    public function setPropertyClass($propertyClass) {
        $this->propertyClass = $propertyClass;
        return $this;
    }
    
    /**
     * Get entity from repository
     * 
     * This method return the
     * entity basing on it's
     * id from the database, or
     * null.
     * 
     * @param string $entityId The entity id
     * 
     * @return mixed|NULL
     */
    protected function getEntityFromRepository($entityId){
        $entity = $this->entityRepository->find($entityId);
        
        if ($entity) {
            return $entity;
        } else {
            return null;
        }
    }
    
    /**
     * Set entity repository
     * 
     * This method register
     * the entity repository
     * 
     * @param EntityRepository $entityRepository The entity repository.
     * 
     * @return AddCollectionEntityChain
     */
    public function setEntityRepository(EntityRepository $entityRepository){
        $this->entityRepository = $entityRepository;
        return $this;
    }
    
    /**
     * Select entity
     * 
     * This method allow to
     * select the entity as
     * it if entity is given
     * or from the database
     * if the id is passed.
     * 
     * Return null if the entity
     * cannot be resolved.
     * 
     * @param mixed $entityData The entity or it databse id
     * 
     * @return NULL|mixed
     */
    protected function selectEntity($entityData){
        $entity = null;
    
        // Find the project
        if (is_object($entityData) && $entityData instanceof $this->propertyClass) {
            $entity = $entityData;
        } else if (is_string($entityData)) {
            $entity = $this->getEntityFromRepository($entityData);
        }
        
        return $entity;
    }
    
    /**
     * Process
     *
     * This method process
     * the data.
     *
     * @param mixed $action  The requested action
     * @param mixed $data    The data to process
     * @param array $options The optional data
     *
     * @return ChainOfResponsibilityInterface
     */
    public function process($action, $data, array $options = array()){
        $state = false;
    
        if ($this->support($action) && array_key_exists("data", $options)) {
    
            if (is_array($options["data"])) {
                foreach ($options["data"] as $element) {
                    $injectable = $this->selectEntity($element);
                    
                    if ($injectable !== null) {
                        $state = true;
                        $this->inject($data, $injectable);
                    }
                }
            } else {
                $injectable = $this->selectEntity($options["data"]);
                
                if ($injectable !== null) {
                    $state = true;
                    $this->inject($data, $injectable);
                }
            }
    
        }
        
        $this->notifyAll(array("state"=>$state));
        
        if ($this->getNext() !== null) {
            return $this->getNext()->process($action, $data, $options);
        } else {
            return $this;
        }
        
    }
}
