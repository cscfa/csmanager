<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Exception
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Exception\Set;

/**
 * UndefinedBoundException class.
 *
 * The UndefinedBoundException class is an utility
 * tool to throw exception that represent an
 * undefined array index.
 *
 * @category Exception
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UndefinedBoundException extends \Exception
{

    /**
     * Default constructor.
     * 
     * This is the default 
     * UndefinedBoundException
     * constructor.
     * 
     * @param string     $message  The exception message
     * @param integer    $code     The error code
     * @param \Exception $previous The previous exception if exist
     */
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
