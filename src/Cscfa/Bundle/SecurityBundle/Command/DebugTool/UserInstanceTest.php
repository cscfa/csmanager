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
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * UserInstanceTest class.
 *
 * The UserInstanceTest class purpose feater to
 * validate a User parameter.
 *
 * @category Command
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class UserInstanceTest implements TestValueInterface
{
    /**
     * Allow null.
     *
     * This parameter inform
     * that the given value
     * can be null.
     *
     * @var bool
     */
    protected $allowNull;

    /**
     * Default constructor.
     *
     * This constructor allow
     * to give a null as value
     * instead of a User instance.
     *
     * @param bool $allowNull The null allowed state
     */
    public function __construct($allowNull = false)
    {
        $this->allowNull = $allowNull;
    }

    /**
     * The test method.
     *
     * This method allow
     * to test a value
     * asserted as User
     * instance.
     *
     * @param mixed $value The value to test
     * @param mixed $extra An extra element that can be used to test the value
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function test($value, $extra = null)
    {
        if ($value instanceof User || ($value === null && $this->allowNull)) {
            return true;
        } else {
            return false;
        }
    }
}
