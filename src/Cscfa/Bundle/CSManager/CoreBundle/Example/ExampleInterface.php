<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Example
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Example;

/**
 * ExampleInterface interface.
 * 
 * This interface allow to implement
 * some methods to present feature
 * usage.
 *
 * @category Example
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface ExampleInterface
{

    /**
     * The howItWork method.
     * 
     * This method contain usage
     * example of features. It
     * not need arguments and
     * return void.
     * 
     * @return void
     */
    public function howItWork();
}
