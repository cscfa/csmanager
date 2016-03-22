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

namespace Cscfa\Bundle\TwigUIBundle\Interfaces;

/**
 * ImmutableInterface.
 *
 * The ImmutableInterface is used offer desallow
 * modification support.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ImmutableInterface
{
    /**
     * Is immutable.
     *
     * This method return true if the current instance
     * is in immutable state.
     *
     * @return bool
     */
    public function isImmutable();

    /**
     * Set immutable.
     *
     * This method allow to set the current
     * instance into an immutable state.
     *
     * @param bool $immutable The immutable state
     *
     * @return ImmutableInterface
     */
    public function setImmutable($immutable);
}
