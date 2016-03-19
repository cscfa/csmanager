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
 * ObserverInterface.
 *
 * The ObserverInterface is used to define the
 * observer methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ObserverInterface
{
    /**
     * Notify.
     *
     * This method allow the observer to be notified.
     *
     * @param ObserverEventInterface $event The event
     *
     * @return ObserverInterface
     */
    public function notify(ObserverEventInterface $event);

    /**
     * Is notified.
     *
     * This method return the notification state
     * of the observer.
     *
     * @return bool
     */
    public function isNotified();

    /**
     * Get events.
     *
     * This methods purpose an access to the
     * events that was notified.
     *
     * The retreived events must be a clone of
     * the original to prevent side effects between
     * systems.
     *
     * @return array
     */
    public function getEvents();
}
