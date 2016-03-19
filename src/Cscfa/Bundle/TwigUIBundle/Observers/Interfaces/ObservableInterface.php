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
 * ObservableInterface.
 *
 * The ObservableInterface is used to define the
 * observable methods.
 *
 * @category Interface
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
     * This method allow to store an observer.
     *
     * @param ObserverInterface $observer The observer to store
     */
    public function addObserver(ObserverInterface $observer);

    /**
     * Get observers.
     *
     * This method return the set of stored observers.
     *
     * @return array
     */
    public function getObservers();
}
