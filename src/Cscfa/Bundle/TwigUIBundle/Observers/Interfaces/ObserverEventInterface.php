<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Observers\Interfaces;

/**
 * ObserverEventInterface.
 *
 * The ObserverEventInterface is used to define the
 * observer event methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ObserverEventInterface
{
    /**
     * Set context.
     *
     * This method allow to set the context
     * whence the obervers chain will be
     * retreived.
     *
     * The context is regarded as the event
     * creator, so, the ObservableInterface.
     *
     * @param ObservableInterface $context The event context
     *
     * @return ObserverEventInterface
     */
    public function setContext(ObservableInterface $context);

    /**
     * Get context.
     *
     * This method return the event context.
     *
     * @return ObservableInterface
     */
    public function getContext();

    /**
     * Notify all.
     *
     * This method extract a set of observer
     * from the context and notify each one
     * while the propagation is not prevented
     * or the entire set of observer is reached.
     *
     * @throws \Exception if the context is not set
     *
     * @return ObserverEventInterface
     */
    public function notifyAll();

    /**
     * Add parameter.
     *
     * This method allow to add a parameter too
     * the event to be used by any observer.
     *
     * @param string $alias The key where the parameter will be stored
     * @param mixed  $param The parameter value
     *
     * @return ObserverEventInterface
     */
    public function addParameter($alias, $param);

    /**
     * Get parameter.
     *
     * This method return a parameter designed
     * by it's alias.
     *
     * @param string $alias the parameter alias
     *
     * @throws \Exception if the parameter was never stored
     *
     * @return mixed
     */
    public function getParameter($alias);

    /**
     * Has parameter.
     *
     * This method return true if a parameter
     * was stored as the given alias. Else, will
     * return false.
     *
     * @param string $alias The parameter alias
     *
     * @return bool
     */
    public function hasParameter($alias);

    /**
     * Prevent propagation.
     *
     * This method stop the propagation of the
     * event.
     *
     * @return ObserverEventInterface
     */
    public function preventPropagation();
}
