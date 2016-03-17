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

namespace Cscfa\Bundle\TwigUIBundle\Element\BaseInterface;

/**
 * HTMLTarget interface.
 *
 * The HTMLTarget interface
 * define html target values.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface HTMLTargetInterface
{
    /**
     * Blank.
     *
     * This constant define a target
     * that opens the link in a new
     * window or tab.
     *
     * @var string
     */
    const BLANK_TARGET = '_blank';

    /**
     * Self.
     *
     * This constant define a target
     * that opens the link in the
     * same frame as it was clicked.
     *
     * @var string
     */
    const SELF_TARGET = '_self';

    /**
     * Parent.
     *
     * This constant define a target
     * that opens the link in the
     * parent frame.
     *
     * @var string
     */
    const PARENT_TARGET = '_parent';

    /**
     * Top.
     *
     * This constant define a target
     * that opens the link in the
     * full body of the window.
     *
     * @var string
     */
    const TOP_TARGET = '_top';
}
