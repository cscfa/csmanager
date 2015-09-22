<?php
namespace Cscfa\Bundle\ToolboxBundle\Type\Command;

class DebugingSubtype
{
    private $value;
    protected $labels;
    protected $cols;
    
    protected $errorCount;
    
    public function __construct($value, $selector, $label, $success, $error)
    {
        $this->value = $value;
        $this->labels = array();
        $this->cols = array();
        $this->errorCount = 0;
        
        $this->setLabel($value, $label);
        $this->process($value, $selector, $success, $error);
        $this->garbage();
    }
    
    public function getErrorCount()
    {
        return $this->errorCount;
    }
    
    public function getRow()
    {
        return array_merge($this->labels, $this->cols);
    }
    
    private function garbage()
    {
        unset($this->value);
    }
    
    private function process($value, $selector, $success, $error)
    {
        foreach ($selector as $select){
            $target = $select["target"];
            $test = $select["test"];
            
            $this->cols[$target] = $test($this->getProperty($target), $value);
            
            if (! $this->cols[$target]) {
                $this->errorCount ++;
                $this->cols[$target] = $error;
            }else{
                $this->cols[$target] = $success;
            }
        }
    }
    
    private function setLabel($value, $label)
    {
        foreach ($label as $columnLabel){
            $this->labels[$columnLabel] = $this->getProperty($columnLabel);
        }
    }
    
    private function getProperty($name){
        if(is_array($this->value)){
            return $this->value[$name];
        }else{
            return $this->value->$name();
        }
    }
    
}