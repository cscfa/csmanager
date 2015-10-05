<?php
/**
 * This file is a part of CSCFA TwigUi project.
 * 
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Tag
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\TwigUIBundle\Element\Base;

use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\NestedInterface;
/**
 * TextTag class.
 *
 * The TextTag class to build a
 * xml text tag.
 *
 * @category Tag
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class TextTag implements NestedInterface
{

    /**
     * The content.
     * 
     * This property indicate the
     * current text tag content.
     * 
     * @var string
     */
    protected $content;
    
    /**
     * The nesting level.
     * 
     * This property indicate
     * the nested level of the
     * current tag.
     * 
     * @var integer
     */
    protected $nestedLevel;

    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     * 
     * @param string $content The text content
     */
    public function __construct($content = "")
    {
        $this->content = $content;
        $this->nestedLevel = 0;
    }

    /**
     * Get content.
     * 
     * This method return the
     * current text tag content.
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content.
     * 
     * This method allow
     * to set the current
     * text tag content.
     * 
     * @param string $content the new content.
     * 
     * @return TextTag
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Get nested level.
     * 
     * This method return
     * the current tag nesting
     * level.
     * 
     * @return integer
     */
    public function getNestedLevel()
    {
        return $this->nestedLevel;
    }
    
    /**
     * Set nested level.
     * 
     * This method allow to
     * set the current tag
     * nesting level.
     * 
     * @param integer $nestedLevel The nesteing level
     * 
     * @return Tag
     */
    public function setNestedLevel($nestedLevel)
    {
        $this->nestedLevel = $nestedLevel;
        return $this;
    }
    
    /**
     * To string.
     * 
     * This method return the current
     * instance parsed as string.
     * 
     * @return string
     */
    public function __toString()
    {
        return str_repeat("\t", $this->getNestedLevel()).$this->content."\n";
    }
}
