<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Converter
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Converter\Command;

/**
 * CommandTypeConverter class.
 *
 * The CommandTypeConverter class is used
 * to convert instance and type in a command
 * context.
 *
 * @category Converter
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CommandTypeConverter
{

    /**
     * Convert to string
     * 
     * This method allow to convert
     * a type or an instance to string.
     * 
     * Supported convertion :
     * <ul>
     *      <li>array</li>
     *      <li>boolean</li>
     *      <li>DateTime</li>
     *      <li>null</li>
     * </ul>
     * 
     * @param mixed $element The element to convert
     * 
     * @return string|mixed
     */
    static function convertToString($element)
    {
        if (is_array($element)) {
            $element = implode(", ", $element);
        } else if (is_bool($element)) {
            $element = $element ? 'true' : 'false';
        } else if ($element instanceof \DateTime) {
            $element = $element->format("Y-m-d H:i:s");
        } else if ($element === null) {
            $element = "NULL";
        }
        
        return $element;
    }
}