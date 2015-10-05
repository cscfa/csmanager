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
 * StylizedInterface interface.
 *
 * The StylizedInterface interface
 * define stylization methods.
 *
 * @category Interface
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface StylizedInterface
{

    /**
     * Has style.
     * 
     * This method check if the
     * current element has style.
     * 
     * @return boolean
     */
    public function hasStyle();
    
    /**
     * Get styles.
     * 
     * This method return all
     * of the element stylesheets.
     * 
     * @return array
     */
    public function getStyles();
    
}
