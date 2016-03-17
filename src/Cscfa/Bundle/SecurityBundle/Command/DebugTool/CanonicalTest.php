<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Command
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     * @return bool
     */
    public function test($value, $extra = null)
    {
        if (!isset($extra['main'])) {
            throw new UndefinedBoundException("The 'main' index is unexisting", 404);
        }

        $mainElement = $extra['main'];
        if (!method_exists($mainElement, $this->getter)) {
            $message = sprintf(
                'The %s method is unimplemented for the %s class',
                $this->getter,
                get_class($mainElement)
            );
            throw new MethodUnimplementedException($message, 500);
        }

        if ($value == strtolower($mainElement->{$this->getter}())) {
            return true;
        } else {
            return false;
        }
    }
}
