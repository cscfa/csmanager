<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Command\DebugTool;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Test\TestValueInterface;
use Cscfa\Bundle\ToolboxBundle\Exception\Set\UndefinedBoundException;
use Cscfa\Bundle\ToolboxBundle\Exception\Method\MethodUnimplementedException;

/**
 * CanonicalTest class.
 *
 * The CanonicalTest class purpose feater to
 * validate a canonical parameter.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CanonicalTest implements TestValueInterface
{
    /**
     * The getter value.
     * 
     * This represent the 
     * getter method for the
     * not canonical value.
     * 
     * @var string
     */
    protected $getter;
    
    /**
     * CanonicalTest constructor.
     * 
     * The default CanonicalTest
     * constructor that define the
     * method getter name for the
     * not canonical value.
     * 
     * @param string $getter The getter method for the not canonical getter method
     */
    public function __construct($getter)
    {
        $this->getter = $getter;
    }

    /**
     * The test method.
     *
     * This method allow
     * to test a value that
     * is asserted as canonical
     * value with a not 
     * canonical value accessible
     * by a getter method.
     *
     * @param mixed $value The value to test
     * @param mixed $extra An extra element that can be used to test the value
     *
     * @return boolean
     */
    public function test($value, $extra = null)
    {
        if (!isset($extra["main"])) {
            throw new UndefinedBoundException("The 'main' index is unexisting", 404);
        }
        
        $mainElement = $extra["main"];
        if (!method_exists($mainElement, $this->getter)) {
            throw new MethodUnimplementedException("The ".$this->getter." method is unimplemented for the ".get_class($mainElement)." class", 500);
        }
        
        if ($value == strtolower($mainElement->{$this->getter}())) {
            return true;
        } else {
            return false;
        }
    }
}
