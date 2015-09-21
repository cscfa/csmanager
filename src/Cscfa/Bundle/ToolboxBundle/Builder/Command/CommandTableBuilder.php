<?php
/**
 * This file is a part of CSCFA toolbox project.
 * 
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Builder
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Builder\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\ToolboxBundle\Converter\Command\CommandTypeConverter;

/**
 * CommandTableBuilder class.
 *
 * The CommandTableBuilder class is used
 * to build table view.
 *
 * @category Builder
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CommandTableBuilder
{
    /**
     * An element type.
     * 
     * This value represent
     * an array element.
     * 
     * @var integer
     */
    const TYPE_ARRAY = 0;
    
    /**
     * An element type.
     * 
     * This value represent
     * an object element.
     * 
     * @var integer
     */
    const TYPE_OBJECT = 1;
    
    /**
     * The array of value.
     * 
     * This parameter represent
     * an array where is stored
     * the elements to use.
     * 
     * @var array
     */
    protected $arrayValues;
    
    /**
     * The array of keys.
     * 
     * This parameter represent
     * the array of keys to use
     * as label and getter.
     * 
     * Use an associative array
     * with label as key and value
     * as setter. If the element
     * is an array, use the index
     * as getter. If the element
     * is an object, use the getter
     * method name as value.
     * 
     * @var array
     */
    protected $elementKeys;
    
    /**
     * The type of the element.
     * 
     * Can be set as 0 for an array
     * or 1 to an object.
     * 
     * @var integer
     */
    protected $elementType;
    
    /**
     * The CommandTableBuilder constructor.
     * 
     * This method is the default
     * CommandTableBuilder constructor
     * that set the type as array by
     * default. The other parameters
     * will be sets at an empty array
     * by default.
     * 
     * @param integer $type   The type of elements that stored as values
     * @param array   $values An array of elements
     * @param array   $keys   An associative array with label as key and method or index as value
     */
    public function __construct($type = self::TYPE_ARRAY, array $values = array(), array $keys = array())
    {
        $this->elementType = $type;
        $this->arrayValues = $values;
        $this->elementKeys = $keys;
    }

    /**
     * Get values.
     * 
     * This method return
     * the array of elements
     * to use as values.
     * 
     * @return array
     */
    public function getValues()
    {
        return $this->arrayValues;
    }

    /**
     * Set values.
     * 
     * Set the array to
     * use as value an
     * contain elements.
     * 
     * @param array $values The array of values
     * 
     * @return CommandTableBuilder
     */
    public function setValues($values)
    {
        if(!is_array($values)){
            return $this->setValues(array());
        }
        
        $this->arrayValues = $values;
        return $this;
    }

    /**
     * Get the keys.
     * 
     * This method return
     * the associative array
     * to use as label and 
     * getters.
     * 
     * @return array
     */
    public function getKeys()
    {
        return $this->elementKeys;
    }

    /**
     * Set keys.
     * 
     * Set the associative
     * array that contain the
     * labels and elements
     * getters.
     * 
     * @param array $keys The associative array that contain label as keys and getter as values.
     * 
     * @return CommandTableBuilder
     */
    public function setKeys($keys)
    {
        if(!is_array($keys)){
            return $this->setKeys(array());
        }
        
        $this->elementKeys = $keys;
        return $this;
    }

    /**
     * Get the element type.
     * 
     * This method allow to
     * get the type of elements
     * that are stored into
     * the values array.
     * 
     * @return integer
     */
    public function getType()
    {
        return $this->elementType;
    }

    /**
     * Set the element type.
     * 
     * Set the value of the
     * element type.
     * 
     * @param integer $type The element type value
     * 
     * @return CommandTableBuilder
     */
    public function setType($type)
    {
        $this->elementType = $type;
        return $this;
    }
    
    /**
     * Get the rows.
     * 
     * This method allow to get
     * the rows of the table to 
     * create. The result is
     * processed from the values
     * and keys parameters.
     * 
     * @return array
     */
    public function getRows()
    {
        $rows = array();
        
        foreach ($this->arrayValues as $value) {
            $rows[] = array();
            $currentKey = count($rows) - 1;
            
            if($this->elementType == self::TYPE_ARRAY){
                foreach ($this->elementKeys as $key) {
                    if(isset($value[$key])){
                        $rows[$currentKey][] = CommandTypeConverter::convertToString($value[$key]);
                    }else{
                        $rows[$currentKey][] = null;
                    }
                }
            }else if($this->elementType == self::TYPE_OBJECT){
                foreach ($this->elementKeys as $key) {
                    if(method_exists($value, $key)){
                        $rows[$currentKey][] = CommandTypeConverter::convertToString($value->$key());
                    }else{
                        $rows[$currentKey][] = null;
                    }
                }
            }
        }
        
        return $rows;
    }
    
    /**
     * Get header.
     * 
     * This method allow to get
     * the header array. The
     * result is processed from
     * the keys parameter.
     * 
     * @return array
     */
    public function getHeader(){
        $header = array();
        
        $keys = array_keys($this->elementKeys);
        foreach ($keys as $key){
            $header[] = $key;
        }
        
        return $header;
    }
    
    /**
     * Render the table.
     * 
     * This method allow to
     * create a table and 
     * render it.
     * 
     * @param OutputInterface $output
     */
    public function render(OutputInterface $output)
    {
        $table = new Table($output);
        
        $table->setHeaders($this->getHeader());
        $table->setRows($this->getRows());
        $table->render();
    }
 
}
