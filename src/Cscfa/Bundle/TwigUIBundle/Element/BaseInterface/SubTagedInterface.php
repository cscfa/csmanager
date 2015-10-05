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
 * SubTagedInterface interface.
 *
 * The SubTagedInterface interface
 * is used to get tags conatined 
 * into another tags.
 *
 * @category Interface
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface SubTagedInterface
{

    /**
     * Get sub tags.
     * 
     * Return all of the Tag
     * childs.
     * 
     * @return array
     */
    public function getSubTags();

    /**
     * Get sub tags count.
     * 
     * Return the sub tags count.
     * 
     * @return integer
     */
    public function getSubTagsCount();
}