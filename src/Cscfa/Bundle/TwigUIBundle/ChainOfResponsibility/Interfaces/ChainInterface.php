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

namespace Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Interfaces;

/**
 * ChainInterface.
 *
 * The ChainInterface is used define the
 * chain of responsibility methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ChainInterface
{
    /**
     * Set next.
     *
     * This method allow to register the
     * next ChainInterface of the current.
     *
     * @param ChainInterface $next The next chain
     *
     * @return ChainInterface
     */
    public function setNext(ChainInterface $next);

    /**
     * Get next.
     *
     * This method return the next chain
     * element.
     *
     * @return ChainInterface
     */
    public function getNext();
}
