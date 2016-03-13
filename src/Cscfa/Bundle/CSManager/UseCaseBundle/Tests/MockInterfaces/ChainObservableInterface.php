<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockInterfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObservableInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObserverInterface;

/**
 * ChainObservableInterface.
 *
 * The ChainObservableInterface
 * define an ChainOfResponsabilityInterface
 * and ObservableInterface implemented
 * interface.
 *
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface ChainObservableInterface extends ChainOfResponsibilityInterface, ObservableInterface {
    
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
    
    /**
     * Add observer
     * 
     * This method allow to
     * register a new observer.
     * 
     * @param ObserverInterface $observer The observer to register
     * 
     * @return ObservableInterface
     */
    public function addObserver(ObserverInterface $observer);
    
    /**
     * Get observer
     * 
     * This method return the
     * set of registered observers.
     * 
     * @return array:ObserverInterface
     */
    public function getObserver();
    
    /**
     * Notify all
     * 
     * This method notify all
     * of the registered
     * observers.
     * 
     * @param mixed $data The extra data
     * 
     * @return ObservableInterface
     */
    public function notifyAll($data = null);
}