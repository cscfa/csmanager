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

/**
 * DateTimeTest class.
 *
 * The DateTimeTest class purpose feater to
 * validate a DateTime parameter.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DateTimeTest implements TestValueInterface
{
    
    /**
     * Allow null.
     * 
     * This option allow to
     * give a null value as
     * DateTime instance.
     * 
     * @var integer
     */
    const ALLOW_NULL = 1;
    
    /**
     * Before now allowed.
     * 
     * This option allow to
     * give a DateTime instance
     * that represent a date
     * before the current
     * DateTime.
     * 
     * @var integer
     */
    const BEFORE_NOW = 2;
    
    /**
     * After now allowed.
     * 
     * This option allow to
     * give a DateTime instance
     * that represent a date
     * after the current
     * DateTime.
     * 
     * @var integer
     */
    const AFTER_NOW = 4;
    
    /**
     * Current allowed.
     * 
     * This option allow to
     * give a DateTime instance
     * that represent a date
     * equal to the current
     * DateTime.
     * 
     * @var integer
     */
    const CURRENT = 8;
    
    /**
     * Allow all.
     * 
     * This option allow to 
     * give null or DateTime.
     * 
     * @var integer
     */
    const ALL_ALLOWED = 15;
    
    /**
     * The options.
     * 
     * This parameter register
     * the options to use
     * to validate the DateTime
     * instance.
     * 
     * @var integer
     */
    protected $options;
    
    /**
     * Default constructor.
     * 
     * This constructor allow
     * to register the options
     * to use to validate the
     * DateTime instance.
     * 
     * @param integer $options The option to use to validate the DateTime instance
     */
    public function __construct($options = 0)
    {
        $this->options = $options;
    }

    /**
     * The test method.
     *
     * This method allow
     * to test a DateTime
     * instance.
     *
     * @param mixed $value The value to test
     * @param mixed $extra An extra element that can be used to test the value
     *
     * @return boolean
     */
    public function test($value, $extra = null)
    {
        $nullAllowed = ((boolean)($this->options & self::ALLOW_NULL));
        $beforeNowAllowed = ((boolean)($this->options & self::BEFORE_NOW));
        $afterNowAllowed = ((boolean)($this->options & self::AFTER_NOW));
        $currentAllowed = ((boolean)($this->options & self::CURRENT));
        
        if ($value === null && $nullAllowed) {
            return true;
        } else if (($value === null && !$nullAllowed) || !($value instanceof \DateTime)) {
            return false;
        }
        
        $current = new \DateTime();
        if (($value > $current && !$afterNowAllowed) || ($value < $current && !$beforeNowAllowed) || ($value == $current && !$currentAllowed)) {
            return false;
        }
        
        return true;
    }
}
