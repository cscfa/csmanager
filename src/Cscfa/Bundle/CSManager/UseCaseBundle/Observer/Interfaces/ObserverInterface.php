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
 * ObserverInterface.
 *
 * The ObserverInterface
 * define the observer
 * methods
 *
 * @category Observer
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
     * This method allow the
     * observer to be notified.
     *
     * @param ObservableInterface $observable The notifyer
     * @param mixed               $extra      The extra data
     *
     * @return ObserverInterface
     */
    public function notify(ObservableInterface $observable, $extra = null);
}
