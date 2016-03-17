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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Element\Advanced\Page;

use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\NestedInterface;

/**
 * Doctype class.
 *
 * The Doctype class is used
 * to get an html page doctype.
 *
 * @category Tag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class Doctype implements NestedInterface
{
    /**
     * HTML5.
     *
     * This constant represent an
     * HTML5 doctype.
     *
     * @var int
     */
    const HTML5 = 0;

    /**
     * HTML401_STRICT.
     *
     * This constant represent an
     * HTML4.01 strict doctype.
     *
     * @var int
     */
    const HTML401_STRICT = 1;

    /**
     * HTML401_TRANSITIONAL.
     *
     * This constant represent an
     * HTML4.01 transitional doctype.
     *
     * @var int
     */
    const HTML401_TRANSITIONAL = 2;

    /**
     * HTML401_FRAMESET.
     *
     * This constant represent an
     * HTML4.01 frameset doctype.
     *
     * @var int
     */
    const HTML401_FRAMESET = 3;

    /**
     * XHTML1_STRICT.
     *
     * This constant represent an
     * XHTML1.0 strict doctype.
     *
     * @var int
     */
    const XHTML1_STRICT = 4;

    /**
     * XHTML1_TRANSITIONAL.
     *
     * This constant represent an
     * XHTML1.0 transitional doctype.
     *
     * @var int
     */
    const XHTML1_TRANSITIONAL = 5;

    /**
     * XHTML1_FRAMESET.
     *
     * This constant represent an
     * XHTML1.0 frameset doctype.
     *
     * @var int
     */
    const XHTML1_FRAMESET = 6;

    /**
     * XHTML11.
     *
     * This constant represent an
     * XHTML1.1 doctype.
     *
     * @var int
     */
    const XHTML11 = 7;

    /**
     * The doctype.
     *
     * This property indicate the
     * current doctype.
     *
     * @var int
     */
    protected $doctype;

    /**
     * The nesting level.
     *
     * This property indicate the
     * nesting level of the current
     * element.
     *
     * @var int
     */
    protected $nestedLevel;

    /**
     * Default constructor.
     *
     * This constructor initialize the
     * properties.
     *
     * @param int $doctype The doctype to use
     */
    public function __construct($doctype)
    {
        $this->doctype = $doctype;
    }

    /**
     * Get doctype.
     *
     * This method return the
     * current doctype identifier.
     *
     * @return number
     */
    public function getDoctype()
    {
        return $this->doctype;
    }

    /**
     * Set doctype.
     *
     * This method allow to set
     * the current doctype.
     *
     * Note that you can use the
     * Doctype constants.
     *
     * @param int $doctype The doctype to use
     *
     * @return Doctype
     */
    public function setDoctype($doctype)
    {
        $this->doctype = $doctype;

        return $this;
    }

    /**
     * Get nested level.
     *
     * This method return
     * the current tag nesting
     * level.
     *
     * @return int
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
     * @param int $nestedLevel The nesteing level
     *
     * @return mixed
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
        return str_repeat("\t", $this->nestedLevel).$this->getDoctypeAsString()."\n";
    }

    /**
     * Get doctype as string.
     *
     * This method convert the current
     * doctype into html tag.
     *
     * @return string
     */
    protected function getDoctypeAsString()
    {
        $baseHtml = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML ';
        $baseXhtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML ';
        switch ($this->doctype) {
            case self::HTML5:
                return '<!DOCTYPE html>';
            case self::HTML401_STRICT:
                return $baseHtml.'4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> ';
            case self::HTML401_TRANSITIONAL:
                return $baseHtml.'4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> ';
            case self::HTML401_FRAMESET:
                return $baseHtml.'4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd"> ';
            case self::XHTML1_STRICT:
                return $baseXhtml.'1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> ';
            case self::XHTML1_TRANSITIONAL:
                return $baseXhtml.'1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
            case self::XHTML1_FRAMESET:
                return $baseXhtml.'1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
            case self::XHTML11:
                return $baseXhtml.'1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
            default:
                return '';
        }
    }
}
