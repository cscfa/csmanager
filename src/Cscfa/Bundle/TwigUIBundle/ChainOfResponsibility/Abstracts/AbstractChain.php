<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Abstracts;

use Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Interfaces\ChainInterface;

/**
 * AbstractChain.
 *
 * The AbstractChain define base process for
 * the chain.
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractChain implements ChainInterface
{
    /**
     * Next chain.
     *
     * This property store the
     * next chain instance.
     *
     * @var ChainInterface
     */
    protected $nextChain;

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
    public function setNext(ChainInterface $next)
    {
        $this->nextChain = $next;

        return $this;
    }

    /**
     * Get next.
     *
     * This method return the next chain
     * element.
     *
     * @return ChainInterface
     */
    public function getNext()
    {
        return $this->nextChain;
    }
}
