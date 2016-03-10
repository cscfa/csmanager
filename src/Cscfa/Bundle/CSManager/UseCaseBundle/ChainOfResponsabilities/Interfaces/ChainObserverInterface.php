<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Observer
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObserverInterface;

/**
 * ChainObserver.
 *
 * The ChainObserver
 * define the observer
 * process for the chain
 * that implements observable
 * and ChainOfResponsibility
 * interface.
 *
 * @category Observer
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface ChainObserverInterface extends ObserverInterface {
    
    /**
     * Is used
     * 
     * This method return the
     * used state of the observed
     * chain.
     * 
     * @return boolean
     */
    public function isUsed();
    
    /**
     * Get actions
     * 
     * This method return the
     * list of actions that
     * the observed chain can
     * support.
     * 
     * @return array:mixed
     */
    public function getActions();
    
}