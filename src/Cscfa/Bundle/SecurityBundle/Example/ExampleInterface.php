<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Example
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\SecurityBundle\Example;

/**
 * ExampleInterface interface.
 *
 * This interface allow to implement
 * some methods to present feature
 * usage.
 *
 * @category Example
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     */
    public function howItWork();
}
