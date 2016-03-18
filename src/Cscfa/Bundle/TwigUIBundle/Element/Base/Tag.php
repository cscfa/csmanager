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

namespace Cscfa\Bundle\TwigUIBundle\Element\Base;

use Cscfa\Bundle\TwigUIBundle\Element\Base\Attr\MultipleAttr;
use Cscfa\Bundle\ToolboxBundle\Set\HackSet;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\NestedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\SubTagedInterface;

/**
 * Tag class.
 *
 * The Tag class to build a
 * xml tag.
 *
 * @category Tag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class Tag implements NestedInterface, SubTagedInterface
{
    /**
     * The tag name.
     *
     * This property indicate
     * the current tag name.
     *
     * @var string
     */
    protected $tagName;

    /**
     * The tag Id.
     *
     * This property indicate
     * the current tag id.
     *
     * @var string
     */
    protected $tagId;

    /**
     * The tag class.
     *
     * This property indicate
     * the current tag class.
     *
     * @var MultipleAttr
     */
    protected $class;

    /**
     * The tag attributes.
     *
     * This property register all
     * of the current tag attribute.
     *
     * @var HackSet
     */
    protected $attributes;

    /**
     * The tag childs.
     *
     * This property register the
     * current tag childs.
     *
     * @var ListSet
     */
    protected $childs;

    /**
     * The tag inline state.
     *
     * This property indicate that
     * the current tag is inline.
     *
     * Can be overrided if the tag
     * has child.
     *
     * False as default.
     *
     * @var bool
     */
    protected $inline;

    /**
     * The nesting level.
     *
     * This property indicate
     * the nested level of the
     * current tag.
     *
     * @var int
     */
    protected $nestedLevel;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the properties.
     *
     * @param string $tagName The tag name.
     */
    public function __construct($tagName = '')
    {
        $this->tagName = $tagName;
        $this->tagId = '';
        $this->class = new MultipleAttr();
        $this->attributes = new HackSet();
        $this->childs = new ListSet();
        $this->inline = false;
        $this->nestedLevel = 0;
    }

    /**
     * Get tag name.
     *
     * This method return the
     * current tag name.
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set tag name.
     *
     * This method allow to
     * set the current tag
     * name.
     *
     * @param string $tagName The tag name.
     *
     * @return Tag
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get id.
     *
     * This method allow to
     * get the current tag
     * id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->tagId;
    }

    /**
     * Set id.
     *
     * This method allow to
     * set the current tag
     * id.
     *
     * @param string $tagId The id.
     *
     * @return Tag
     */
    public function setId($tagId)
    {
        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Get class set.
     *
     * This method allow to
     * get the current tag
     * class set.
     *
     * @return MultipleAttr
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the class set.
     *
     * This method allow to
     * set the current tag
     * class set.
     *
     * @param MultipleAttr $class The class set
     *
     * @return Tag
     */
    public function setClass(MultipleAttr $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get attributes set.
     *
     * This method allow to
     * get the current tag
     * attributes set.
     *
     * @return HackSet
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set attributes set.
     *
     * This method allow to
     * set the current tag
     * attributes set.
     *
     * @param HackSet $attributes The attributes set.
     *
     * @return Tag
     */
    public function setAttributes(HackSet $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Add child.
     *
     * This method allow to add
     * a child into the child set.
     *
     * Note this method grant the
     * nesting level usage.
     *
     * @param NestedInterface $child The child to add
     *
     * @return Tag
     */
    public function addChild(NestedInterface $child)
    {
        $child->setNestedLevel($this->getNestedLevel() + 1);
        $this->childs->add($child);

        return $this;
    }

    /**
     * Get childs set.
     *
     * This method allow to
     * get the current tag
     * childs set.
     *
     * @return ListSet
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set childs set.
     *
     * This method allow to
     * set the current tag
     * childs set.
     *
     * @param ListSet $childs The childs set.
     *
     * @return Tag
     */
    public function setChilds(ListSet $childs)
    {
        $this->childs = $childs;

        if (!$childs->isEmpty()) {
            $this->setInline(false);
        }

        return $this;
    }

    /**
     * Is inline.
     *
     * This method indicate that
     * the current tag is inline.
     *
     * @return bool
     */
    public function isInline()
    {
        return $this->inline;
    }

    /**
     * Set inline.
     *
     * This method allow to set
     * the current tag as inline.
     *
     * @param bool $inline The current tag inline state.
     *
     * @return Tag
     */
    public function setInline($inline)
    {
        $this->inline = (boolean) $inline;

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
     * @return Tag
     */
    public function setNestedLevel($nestedLevel)
    {
        $this->nestedLevel = $nestedLevel;

        foreach ($this->childs->getAll() as $child) {
            if ($child instanceof NestedInterface) {
                $child->setNestedLevel($this->getNestedLevel() + 1);
            }
        }

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
        if (!$this->childs->isEmpty()) {
            $this->setInline(false);
        }

        return $this->computeTag();
    }

    /**
     * Compute tag.
     *
     * This method return a string
     * that contain the current tag
     * HTML representation.
     *
     * @return string
     */
    protected function computeTag()
    {
        $start = str_repeat("\t", $this->getNestedLevel()).'<';
        $tagName = strtolower($this->tagName);
        $start .= $tagName;

        $attrs = $this->computeAttributesToString();
        if (strlen($attrs) > 0) {
            $start .= ' '.$attrs;
        }

        if ($this->isInline()) {
            return $start."/>\n";
        } else {
            $start .= ">\n";
            $end = str_repeat("\t", $this->getNestedLevel())."</$tagName>\n";
        }

        $content = '';
        foreach ($this->childs->getAll() as $child) {
            if (is_object($child) && method_exists($child, '__toString')) {
                $content .= $child->__toString();
            }
        }

        return $start.$content.$end;
    }

    /**
     * Compute attributes to string.
     *
     * This method return a string
     * that represent the attributes
     * of the current tag.
     *
     * @return string
     */
    protected function computeAttributesToString()
    {
        if (!empty($this->tagId)) {
            $tagId = 'id="'.$this->escapeString($this->tagId).'"';
        } else {
            $tagId = '';
        }

        if (!$this->class->isEmpty()) {
            $class = 'class="'.$this->escapeString($this->class->__toString()).'"';
        } else {
            $class = '';
        }

        if (!$this->attributes->isEmpty()) {
            $attrs = $this->computeHackSetAttrToString($this->attributes);
        } else {
            $attrs = '';
        }

        $result = implode(' ', array($tagId, $class, $attrs));
        while (strpos($result, '  ')) {
            $result = str_replace('  ', ' ', $result);
        }

        return trim($result);
    }

    /**
     * Compute HackSet attributes to string.
     *
     * This method return a string that
     * represent a HackSet attribute
     * container.
     *
     * @param HackSet $hSet The HackSet to dump
     *
     * @return string
     */
    protected function computeHackSetAttrToString(HackSet $hSet)
    {
        $strings = array();
        $keys = $hSet->getKeys();

        foreach ($keys as $key) {
            $strings[] = $key.'="'.$this->escapeString(implode(' ', $hSet->get($key))).'"';
        }

        return implode(' ', $strings);
    }

    /**
     * Escape string.
     *
     * This method return a
     * string with HTML chars
     * escaped.
     *
     * @param string $string The string to escape
     *
     * @return string
     */
    protected function escapeString($string)
    {
        $string = str_replace('\"', '&quot;', $string);
        $string = str_replace("\'", '&#039;', $string);

        return htmlspecialchars($string);
    }

    /**
     * Get sub tags.
     *
     * Return all of the Tag
     * childs.
     *
     * @return array
     */
    public function getSubTags()
    {
        $result = array();

        if ($this->childs->isEmpty()) {
            return array();
        } else {
            foreach ($this->childs->getAll() as $child) {
                $result[] = $child;

                if ($child instanceof SubTagedInterface) {
                    foreach ($child->getSubTags() as $subChild) {
                        $result[] = $subChild;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get sub tags count.
     *
     * Return the sub tags count.
     *
     * @return int
     */
    public function getSubTagsCount()
    {
        $result = 0;

        if (!$this->childs->isEmpty()) {
            foreach ($this->childs->getAll() as $child) {
                ++$result;

                if ($child instanceof SubTagedInterface) {
                    $result += $child->getSubTagsCount();
                }
            }
        }

        return $result;
    }
}
