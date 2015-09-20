<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\BaseInterface\Error;

/**
 * ErrorRegisteryInterface interface.
 *
 * The ErrorRegisteryInterface 
 * interface is used to access 
 * to a last error parameter.
 *
 * @category Interface
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface ErrorRegisteryInterface
{

    /**
     * An error type.
     *
     * This constant represent a no
     * error state.
     *
     * The default value of this constant
     * is an integer set to -1.
     *
     * @var integer
     */
    const NO_ERROR = - 1;

    /**
     * Get the last error.
     *
     * This method allow to get the
     * last error state.
     *
     * @return number
     */
    public function getLastError();
}
