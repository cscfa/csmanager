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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainObserverInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObservableInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

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
class ChainObserver implements ChainObserverInterface {
    
    /**
     * Used
     * 
     * Store the used state
     * of the current chain
     * 
     * @var boolean
     */
    protected $used;
    
    /**
     * Actions
     * 
     * The chain supported
     * actions
     * 
     * @var array
     */
    protected $actions;
    
    /**
     * Constructor
     * 
     * The default ChainObserver
     * class constructor.
     * 
     * @param string $used    The chain used state
     * @param array  $actions The action list of the chain
     */
    public function __construct($used = false, array $actions = array()){
        $this->used = boolval($used);
        $this->actions = $actions;
    }
    
    /**
     * Notify
     * 
     * This method allow the
     * observer to be notified.
     * 
     * @param ObservableInterface $observable The notifyer
     * @param mixed               $extra      The extra data
     * 
     * @return ObserverInterface
     */
    public function notify(ObservableInterface $observable, $extra = null){
        
        if ($observable instanceof ChainOfResponsibilityInterface) {
            array_push($this->actions, $observable->getAction());
            
            if (is_array($extra) && array_key_exists("state", $extra) && is_bool($extra["state"])) {
                if ($extra["state"]) {
                    $this->used = true;
                }
            }
        }

        return $this;
        
    }
    
    /**
     * Get actions
     * 
     * This method return the
     * list of actions that
     * the observed chain can
     * support.
     * 
     * @return array:mixed
     * @see ChainObserverInterface::getActions()
     */
    public function getActions() {
        return $this->actions;
    }
    
    /**
     * Is used
     * 
     * This method return the
     * used state of the observed
     * chain.
     * 
     * @return boolean
     * @see ChainObserverInterface::isUsed()
     */
    public function isUsed() {
        return $this->used;
    }
}
