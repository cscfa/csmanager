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
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainObserverInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Exception\UnusedChainException;

/**
 * EndChain
 * 
 * The endchain process errors in case of the
 * chain come to term without processing the data.
 * 
 * This mustance need that each part of the chain 
 * is defined with the same ChainObserverInterface
 * instance and each passed 'state' key value to
 * extra data.
 * 
 * The given 'state' value extra data must be true
 * if the instance effectivelly process the data.
 * 
 * By default, the EndChain throw an exception if
 * the data wasn't processed. This comportment can
 * be avoided by passing false to the method
 * EndChain::setThrowable(boolean).
 * 
 * @category ChainOfResponsibility
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class EndChain extends AbstractChain{

    /**
     * Throwable
     * 
     * This property define
     * the throwing comportment
     * of the unprocessing state.
     * 
     * @var boolean
     */
    protected $throwable;
    
    /**
     * Constructor
     * 
     * The default EndChain
     * constructor.
     * 
     * @param boolean $throwable The throwing state
     * 
     * @return EndChain
     */
    public function __construct($throwable = true){
        $this->throwable = $throwable;
        return $this;
    }
    
    /**
     * Set throwable
     * 
     * This method allow to
     * set the throwing state
     * of the endChain.
     * 
     * @param boolean $throwable The throwing state
     * 
     * @return EndChain
     */
    public function setThrowable($throwable = true){
        $this->throwable = $throwable;
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
    public function process($action, &$data, array $options = array()){
        
        if ($this->throwable) {
            $observes = $this->getObserver();
            
            foreach ($observes as $observer) {
                if ($observer instanceof ChainObserverInterface) {
                    if (!$observer->isUsed()) {
                        
                        $actions = array();
                        foreach ($observer->getActions() as $listAction) {
                            if (is_string($listAction) || method_exists($listAction, "__toString")) {
                                array_push($actions, strval($listAction));
                            }
                        }
                        
                        throw new UnusedChainException(
                            sprintf("The action %s doesn't exist. Only [%s] exists", $action, implode(",", $actions)),
                            500
                        );
                    }
                }
            }
        }
        
        if ($this->getNext() !== null) {
            return $this->getNext()->process($action, $data, $options);
        } else if (!$this->throwable) {
            return $this;
        }
        
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
    public function getAction() {
        return "endChain";
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
    public function support($action){
        
        return false;
    }

}
