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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObservableInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObserverInterface;

/**
 * AbstractChain.
 *
 * The AbstractChain
 * process the registration
 * of the next element.
 *
 * @category ChainOfResponsibility
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractChain implements ChainOfResponsibilityInterface, ObservableInterface
{
    /**
     * Next chain.
     *
     * The next chainned
     * instance.
     *
     * @var ChainOfResponsibilityInterface
     */
    protected $nextChain;

    /**
     * Observers.
     *
     * The collection of
     * registered observers.
     *
     * @var array:ObserverInterface
     */
    protected $observers = array();

    /**
     * Process.
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
    abstract public function process($action, &$data, array $options = array());

    /**
     * Set next.
     *
     * This method allow
     * to register the next
     * chained instance.
     *
     * @param ChainOfResponsibilityInterface $next
     *
     * @return ChainOfResponsibilityInterface
     */
    public function setNext(ChainOfResponsibilityInterface $next)
    {
        $this->nextChain = $next;

        return $this;
    }

    /**
     * Get next.
     *
     * This method return
     * the next chained
     * instance.
     *
     * @return ChainOfResponsibilityInterface
     */
    public function getNext()
    {
        return $this->nextChain;
    }

    /**
     * Support.
     *
     * This method check if
     * the current chained
     * instance support the
     * given action.
     *
     * @param mixed $action The action
     *
     * @return bool
     */
    abstract public function support($action);

    /**
     * Get action.
     *
     * This method return the
     * action performed by the
     * current chain.
     *
     * @return mixed
     */
    abstract public function getAction();

    /**
     * Add observer.
     *
     * This method allow to
     * register a new observer.
     *
     * @param ObserverInterface $observer The observer to register
     *
     * @return ObservableInterface
     */
    public function addObserver(ObserverInterface $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * Get observer.
     *
     * This method return the
     * set of registered observers.
     *
     * @return array:ObserverInterface
     */
    public function getObserver()
    {
        return $this->observers;
    }

    /**
     * Notify all.
     *
     * This method notify all
     * of the registered
     * observers.
     *
     * @param mixed $data The extra data
     *
     * @return ObservableInterface
     */
    public function notifyAll($data = null)
    {
        foreach ($this->observers as $observer) {
            $observer->notify($this, $data);
        }

        return $this;
    }
}
