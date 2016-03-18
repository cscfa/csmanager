<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Type
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Type\Command;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Test\TestValueInterface;
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;

/**
 * DebugingSubtype class.
 *
 * The DebugingSubtype class is an utility
 * tool to process debug.
 *
 * @category Type
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class DebugingSubtype
{
    /**
     * The value.
     *
     * This parameter register
     * the current value to test.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The labels.
     *
     * This parameter register
     * the current row labels.
     *
     * @var array
     */
    protected $labels;

    /**
     * The columns.
     *
     * This parameter register
     * the current columns rows.
     *
     * @var array
     */
    protected $cols;

    /**
     * The error count.
     *
     * This parameter represent
     * the current error count.
     *
     * @var int
     */
    protected $errorCount;

    /**
     * Default constructor.
     *
     * This constructor allow
     * to create the current debug
     * result.
     *
     * @param mixed  $value    The value to test
     * @param array  $selector The selector that contain the target and the validating class
     * @param array  $label    The label set
     * @param string $success  The string to display if the test success
     * @param string $error    The string to display if the test fail
     */
    public function __construct($value, $selector, $label, $success, $error)
    {
        $this->value = $value;
        $this->labels = array();
        $this->cols = array();
        $this->errorCount = 0;

        $this->setLabel($label);
        $this->process($value, $selector, $success, $error);
        $this->garbage();
    }

    /**
     * Get error count.
     *
     * This method allow to
     * get the current error
     * count.
     *
     * @return number
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * Get row.
     *
     * This method allow
     * to get the debug
     * table row
     * representing the
     * current value.
     *
     * @return array
     */
    public function getRow()
    {
        return array_merge($this->labels, $this->cols);
    }

    /**
     * Garbage.
     *
     * This method unset
     * the value parameter.
     */
    protected function garbage()
    {
        unset($this->value);
    }

    /**
     * Process.
     *
     * This method allow to
     * process the given value
     * to create a row of the
     * debuging table.
     *
     * @param mixed  $value    The value to test
     * @param array  $selector The selector that contain the target and the validating class
     * @param string $success  The string to display if the test success
     * @param string $error    The string to display if the test fail
     *
     * @throws UnexpectedTypeException
     */
    protected function process($value, $selector, $success, $error)
    {
        foreach ($selector as $select) {
            $target = $select['target'];
            $test = $select['test'];

            if (!($test instanceof TestValueInterface)) {
                throw new UnexpectedTypeException(
                    'The test function must be an instance of TestValueInterface.',
                    500,
                    null
                );
            }

            $this->cols[$target] = $test->test($this->getProperty($target), array('main' => $value));

            if (!$this->cols[$target]) {
                ++$this->errorCount;
                $this->cols[$target] = $error;
            } else {
                $this->cols[$target] = $success;
            }
        }
    }

    /**
     * Set label.
     *
     * This method allow
     * to set the label
     * array.
     *
     * @param array $label The label set
     */
    protected function setLabel($label)
    {
        foreach ($label as $columnLabel) {
            $this->labels[$columnLabel] = $this->getProperty($columnLabel);
        }
    }

    /**
     * Get property.
     *
     * This method allow to get
     * a property value.
     *
     * @param string $name The property name to get the value
     */
    protected function getProperty($name)
    {
        if (is_array($this->value)) {
            return $this->value[$name];
        } else {
            return $this->value->$name();
        }
    }
}
