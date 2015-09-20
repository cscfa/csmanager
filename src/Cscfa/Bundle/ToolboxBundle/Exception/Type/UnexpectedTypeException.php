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
namespace Cscfa\Bundle\ToolboxBundle\Exception\Type;

/**
 * UnexpectedTypeException class.
 *
 * The UnexpectedTypeException class is an utility
 * tool to throw exception that represent an
 * unexpected type error.
 *
 * @category Exception
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UnexpectedTypeException extends \Exception
{

    /**
     * The allowed types.
     * 
     * This represent an array
     * of allowed types.
     * 
     * @var array
     */
    protected $allowed;

    /**
     * The exception constructor.
     * 
     * This method is the default
     * unexpected type exception
     * constructor.
     * 
     * @param string    $message  The error message
     * @param integer   $code     The error code
     * @param Exception $previous The previous exception
     * @param array     $allowed  The allowed types
     */
    public function __construct($message, $code, $previous, array $allowed = null)
    {
        $this->allowed = $allowed;
        
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     * 
     * @return string
     * @see    Exception::getMessage()
     */
    public function getMessage()
    {
        if ($this->allowed !== null) {
            return $this->message . "\n Allowed types : " . $this->printArray($this->allowed);
        } else {
            return parent::getMessage();
        }
    }

    /**
     * Print array.
     * 
     * This method return an array
     * as one line string with coma
     * separated values.
     * 
     * @param array $array The array to display
     * 
     * @return string
     */
    protected function printArray(array $array)
    {
        $result = "";
        $array = array_values($array);
        
        foreach ($array as $key => $value) {
            if ($key > 0) {
                $result .= ", ";
            }
            
            $result .= $value;
        }
        
        return $result;
    }
}