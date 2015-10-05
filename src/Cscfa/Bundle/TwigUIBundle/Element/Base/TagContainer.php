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

use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\NestedInterface;

/**
 * TagContainer class.
 *
 * The TagContainer class is 
 * used to create a tag container
 * without self display.
 *
 * @category Tag
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class TagContainer extends Tag
{

    /**
     * Default construtor.
     * 
     * This constructor initialize
     * the properties.
     */
    public function __construct()
    {
        parent::__construct("");
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
        $child->setNestedLevel($this->getNestedLevel());
        $this->childs->add($child);
        
        return $this;
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
        $content = "";
        foreach ($this->childs->getAll() as $child) {
            if (is_object($child) && method_exists($child, "__toString")) {
                $content .= $child->__toString();
            }
        }
        
        return $content;
    }
}