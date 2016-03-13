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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces;

/**
 * ChainOfResponsibilityInterface.
 *
 * The ChainOfResponsibilityInterface
 * define chain of responsibility
 * methods.
 *
 * @category ChainOfResponsibility
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface ChainOfResponsibilityInterface {
    
    /**
     * Set next
     * 
     * This method allow 
     * to register the next
     * chained instance.
     * 
     * @param ChainOfResponsibilityInterface $next
     * 
     * @return ChainOfResponsibilityInterface
     */
    public function setNext(ChainOfResponsibilityInterface $next);
    
    /**
     * Get next
     * 
     * This method return
     * the next chained
     * instance.
     * 
     * @return ChainOfResponsibilityInterface
     */
    public function getNext();
    
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
    public function process($action, &$data, array $options = array());
    
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
    public function support($action);
    
    /**
     * Get action
     * 
     * This method return the
     * action performed by the
     * current chain.
     * 
     * @return mixed
     */
    public function getAction();
    
}
