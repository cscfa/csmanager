<?php
/**
 * This file is a part of CSCFA mail project.
 * 
 * The mail project is a tool bundle written in php
 * with Symfony2 framework to abstract a mail service
 * usage. It prevent the mail service change.
 * 
 * PHP version 5.5
 * 
 * @category Formater
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\MailBundle\Formater;

use Cscfa\Bundle\MailBundle\Formater\Util\BNFElement;

/**
 * BNFFormater class.
 *
 * The BNFFormater class provide 
 * default formating for email
 * backus-Naur format.
 *
 * @category Formater
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class BNFFormater
{

    /**
     * The header.
     * 
     * This property contain
     * the current line header.
     * 
     * @var string
     */
    protected $header;

    /**
     * The elements.
     * 
     * This property indicate
     * each elements of the
     * current line.
     * 
     * @var array
     */
    protected $elements;

    /**
     * Default constructor.
     * 
     * This constructor initilaize
     * the BNFFormater properties.
     */
    public function __construct()
    {
        $this->elements = array();
        $this->header = "";
    }

    /**
     * Add an element.
     * 
     * This method assign a new
     * BNFElement to the element
     * storage.
     * 
     * @return number
     */
    public function addElement()
    {
        $key = count($this->elements);
        $this->elements[] = new BNFElement();
        return $key;
    }

    /**
     * Remove an element.
     * 
     * This method remove an element 
     * from his index if exist.
     * 
     * @param integer $key the element index to remove
     * 
     * @return void
     */
    public function removeElement($key)
    {
        if ($this->hasElement($key)) {
            unset($this->elements[$key]);
        }
    }

    /**
     * Get an element.
     * 
     * This method return an element
     * designed by an index. If this
     * index doesn't exist, return 
     * null.
     * 
     * @param integer $key The element index to get.
     * 
     * @return BNFElement|NULL
     */
    public function getElement($key)
    {
        if ($this->hasElement($key)) {
            return $this->elements[$key];
        } else {
            return null;
        }
    }

    /**
     * Has element.
     * 
     * Check if a key exist into
     * the element storage array.
     * 
     * @param integer $key The index to check
     * 
     * @return boolean
     */
    public function hasElement($key)
    {
        return isset($this->elements[$key]);
    }

    /**
     * Set element.
     * 
     * This method allow to set
     * an element by it's key if
     * exist. If the key doesn't
     * exist, a new one is 
     * assigned. Return the integer
     * key where the element is
     * stored.
     * 
     * @param BNFElement $element The BNFElement to store
     * @param integer    $key     The key where store
     * 
     * @return integer The key where the element is stored
     */
    public function setElement(BNFElement $element, $key)
    {
        if ($this->hasElement($key)) {
            $this->elements[$key] = $element;
        } else {
            $key = $this->addElement();
            $this->elements[$key] = $element;
        }
        
        return $key;
    }

    /**
     * Get all elements.
     * 
     * This method return the storage
     * element's array.
     * 
     * @return array
     */
    public function getAllElements()
    {
        return $this->elements;
    }

    /**
     * Get header.
     * 
     * This method return the
     * current line header.
     * 
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set header.
     * 
     * This method allow to
     * set the current line
     * header.
     * 
     * @param string $header The header to set
     * 
     * @return BNFFormater
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Clear.
     * 
     * This method clear the
     * current instance storage
     * array and header.
     * 
     * @return BNFFormater
     */
    public function clear()
    {
        $this->elements = array();
        $this->header = "";
        
        return $this;
    }

    /**
     * Dump current line.
     * 
     * This method allow to dump
     * the current properties as
     * a string line. Automaticaly
     * assign litteral to name
     * if has litteral but doesn't
     * have name.
     * 
     * @param boolean $withoutComment  Automaticaly skip the comment
     * @param boolean $onlySignificant Automaticaly skip the elements without significants
     * 
     * @return string
     */
    public function dump($withoutComment = false, $onlySignificant = false)
    {
        $result = "";
        
        if ($this->header !== "") {
            $result .= $this->header . ": ";
        }
        
        $keySet = array_keys($this->elements);
        
        foreach ($this->elements as $key => $element) {
            
            if ($element instanceof BNFElement) {
                
                if ($element->hasLitteral() && ! $element->hasName()) {
                    $element->litteralToName();
                }
                
                if ((! $element->hasSignificant() && $onlySignificant) || (! $element->hasSignificant() && $withoutComment)) {
                    if ($key === $keySet[0]) {
                        array_shift($keySet);
                    }
                    continue;
                }
                
                if ($key !== $keySet[0]) {
                    $result .= ", ";
                }
                
                if ($element->getName() !== "") {
                    $result .= $element->getName();
                    
                    if ($element->getLitteral() !== "" || ($element->getComment() !== "" && ! $withoutComment)) {
                        $result .= ' ';
                    }
                }
                
                if ($element->getLitteral() !== "") {
                    $result .= '<' . $element->getLitteral() . '>';
                    
                    if ($element->getComment() !== "" && ! $withoutComment) {
                        $result .= ' ';
                    }
                }
                
                if ($element->getComment() !== "" && ! $withoutComment) {
                    $result .= '(' . $element->getComment() . ')';
                }
            }
        }
        
        return $result;
    }

    /**
     * Parse line.
     * 
     * This method allow to parse
     * a string into BNFElements
     * and header and hydrate the 
     * current instance with the 
     * result.
     * 
     * @param string $string The string to parse
     * 
     * @return BNFFormater
     */
    public function parse($string)
    {
        $this->clear();
        
        if (strpos($string, ':') !== false) {
            $this->header = substr($string, 0, strpos($string, ':'));
            $string = substr($string, strpos($string, ':') + 1);
        }
        
        $elements = explode(",", $string);
        
        foreach ($elements as $element) {
            list ($comment, $element) = $this->extractComment($element);
            list ($litteral, $element) = $this->extractLitteral($element);
            $name = trim($element);
            
            $tmpElement = new BNFElement();
            $tmpElement->setComment($comment)
                ->setLitteral($litteral)
                ->setName($name);
            
            $key = $this->addElement();
            $this->setElement($tmpElement, $key);
        }
        
        return $this;
    }

    /**
     * Extract comments.
     * 
     * This method return an array that
     * contain the comments as string
     * with comma separation in the first
     * index, and the string without
     * comments as second.
     * 
     * @param string $string the string whence extract the comments
     * 
     * @example "name <litteral(comment)>(other comment)" will return array("comment, other comment", "name <litteral>")
     * @return  array
     */
    protected function extractComment($string)
    {
        $comments = array();
        $cutted = 0;
        $commentMatches = array();
        
        preg_match_all("/(\([^)]+\))/", $string, $commentMatches, PREG_OFFSET_CAPTURE);
        
        foreach ($commentMatches[1] as $match) {
            $comments[] .= substr($match[0], 1, strlen($match[0]) - 2);
            
            $tmpString = substr($string, 0, $match[1] - $cutted);
            $tmpString .= substr($string, $match[1] + strlen($match[0]) - $cutted);
            $string = $tmpString;
            $cutted += strlen($match[0]);
        }
        $comment = implode(', ', $comments);
        
        return array(
            $comment,
            $string
        );
    }

    /**
     * Extract litterals.
     * 
     * This method return an array that
     * contain the litterals as string
     * with space separation in the first
     * index, and the string without
     * litterals as second.
     * 
     * @param string $string The string whence extract the litterals
     * 
     * @example "name <litteral>" will return array("litteral", "name ")
     * @return array
     */
    protected function extractLitteral($string)
    {
        $litterals = array();
        $cutted = 0;
        $commentMatches = array();
        
        preg_match_all("/(<[^>]+>)/", $string, $commentMatches, PREG_OFFSET_CAPTURE);
        
        foreach ($commentMatches[1] as $match) {
            $litterals[] .= substr($match[0], 1, strlen($match[0]) - 2);
            
            $tmpString = substr($string, 0, $match[1] - $cutted);
            $tmpString .= substr($string, $match[1] + strlen($match[0]) - $cutted);
            $string = $tmpString;
            $cutted += strlen($match[0]);
        }
        $litteral = implode(' ', $litterals);
        
        return array(
            $litteral,
            $string
        );
    }
}
