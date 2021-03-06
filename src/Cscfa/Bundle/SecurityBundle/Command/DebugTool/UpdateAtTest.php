<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
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
use Cscfa\Bundle\ToolboxBundle\Exception\Method\MethodUnimplementedException;
use Cscfa\Bundle\ToolboxBundle\Exception\Set\UndefinedBoundException;

/**
 * UpdateAtTest class.
 *
 * The UpdateAtTest class purpose feater to
 * validate a 'updatedAt' parameter.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UpdateAtTest implements TestValueInterface
{

    /**
     * The test method.
     *
     * This method allow
     * to test a updatedAt.
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
        if (!method_exists($mainElement, "getUpdatedBy")) {
            throw new MethodUnimplementedException("The getUpdatedBy method is unimplemented for the ".get_class($mainElement)." class", 500);
        }
        
        if ($mainElement->getUpdatedBy() !== null) {
            $dtt = new DateTimeTest(DateTimeTest::BEFORE_NOW);
            return $dtt->test($value);
        } else {
            return ($value === null);
        }
    }
}
