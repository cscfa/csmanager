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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces;

/**
 * ObservableInterface.
 *
 * The ObservableInterface
 * define the observable
 * methods
 *
 * @category Observer
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ObservableInterface
{
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
    public function addObserver(ObserverInterface $observer);

    /**
     * Get observer.
     *
     * This method return the
     * set of registered observers.
     *
     * @return array:ObserverInterface
     */
    public function getObserver();

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
    public function notifyAll($data = null);
}
