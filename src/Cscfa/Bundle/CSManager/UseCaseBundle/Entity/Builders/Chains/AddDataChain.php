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

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain;

/**
 * AddDataChain.
 *
 * The AddDataChain process setting of
 * "specified property" property for
 * array or object.
 *
 * Process "specified property" action.
 *
 * Store in named key for array and try 
 * set{ucfirst("specified property")}()
 * method, or public property "specified 
 * property" before passing responsibility.
 *
 * @category ChainOfResponsibility
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class AddDataChain extends AbstractChain{

    /**
     * Property
     *
     * The property name
     *
     * @var string
     */
    protected $property;
    
    /**
     * Set property
     * 
     * This method allow to set the 
     * property to use.
     * 
     * @param string $property The property name
     * 
     * @return AddDataChain
     */
    public function setProperty($property) {
        $this->property = $property;
        return $this;
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
            
            if (is_array($data)) {
                $state = true;
                $data[$this->property] = $options["data"];
            } else if (is_object($data)) {
                if (in_array("set".ucfirst($this->property), get_class_methods($data))) {
                    $state = true;
                    $data->{"set".ucfirst($this->property)}($options["data"]);
                } else if (in_array($this->property, get_class_vars($data))) {
                    $state = true;
                    $data->{$this->property} = $options["data"];
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

    /**
     * Support
     *
     * This method check if
     * the current chained
     * instance support the
     * given action.
     *
     * @param mixed $action The action
     * 
     * @return boolean
     */
    public function support($action) {
        return $action == $this->property;
    }
    
    /**
     * Get action
     * 
     * This method return the
     * action performed by the
     * current chain.
     * 
     * @return mixed
     */
    abstract public function getAction(){
        return $this->property;
    }

}