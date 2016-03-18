<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Interfaces\UseCaseEntityFactoryInterface;

/**
 * ChainObserverFactory.
 *
 * The ChainObserverFactory
 * provide ChainObserver instance
 * and register the latest of to
 * be injected in multiples services.
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ChainObserverFactory implements UseCaseEntityFactoryInterface
{
    /**
     * Last.
     *
     * The last created
     * instance.
     *
     * @var ChainObserver
     */
    protected $last;

    /**
     * Get last.
     *
     * This method return the
     * last created instance.
     *
     * @return ChainObserver
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * Get instance.
     *
     * This method return an instance
     * of the factory target.
     *
     * @param array $options The creation options
     *
     * @return mixed
     *
     * @see UseCaseEntityFactoryInterface::getInstance()
     */
    public function getInstance(array $options = null)
    {
        $this->last = new ChainObserver();

        return $this->last;
    }
}
