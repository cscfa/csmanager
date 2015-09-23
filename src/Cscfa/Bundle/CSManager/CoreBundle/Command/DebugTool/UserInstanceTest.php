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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Test\TestValueInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;

/**
 * UserInstanceTest class.
 *
 * The UserInstanceTest class purpose feater to
 * validate a User parameter.
 *
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * @var boolean
     */
    protected $allowNull;
    
    /**
     * Default constructor.
     * 
     * This constructor allow
     * to give a null as value
     * instead of a User instance.
     * 
     * @param boolean $allowNull The null allowed state
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
     * @return boolean
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