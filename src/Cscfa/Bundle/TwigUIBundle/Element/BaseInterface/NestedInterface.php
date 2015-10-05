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
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\BaseInterface;

/**
 * NestedInterface interface.
 *
 * The InlineLayout interface
 * is used to create a nest
 * leveled element.
 *
 * @category Interface
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface NestedInterface
{

    /**
     * Get nested level.
     *
     * This method return
     * the current tag nesting
     * level.
     *
     * @return integer
     */
    public function getNestedLevel();

    /**
     * Set nested level.
     *
     * This method allow to
     * set the current tag
     * nesting level.
     *
     * @param integer $nestedLevel The nesteing level
     *
     * @return mixed
     */
    public function setNestedLevel($nestedLevel);
}
