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

namespace Cscfa\Bundle\TwigUIBundle\Element\Advanced\Layout;

use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\StylizedInterface;

/**
 * InlineLayout class.
 *
 * The InlineLayout class
 * is used to create an html
 * inline complex element.
 *
 * @category Tag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class InlineLayout extends Layout implements StylizedInterface
{
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
        $inlineDiv = new Tag('div');
        $inlineDiv->getClass()->add('inline_layout_element');
        $inlineDiv->setNestedLevel($this->getNestedLevel());

        $content = '';
        foreach ($this->childs->getAll() as $child) {
            if (is_object($child) && method_exists($child, '__toString')) {
                $inlineDiv->setChilds(new ListSet());
                $content .= $inlineDiv->addChild($child)->__toString();
            }
        }

        $inlineEnd = new Tag('div');
        $inlineEnd->setNestedLevel($this->getNestedLevel());
        $inlineEnd->getClass()->add('inline_layout_endLine');
        $content .= $inlineEnd->__toString();

        return $content;
    }

    /**
     * Has style.
     *
     * This method check if the
     * current element has style.
     *
     * @return bool
     */
    public function hasStyle()
    {
        return true;
    }

    /**
     * Get styles.
     *
     * This method return all
     * of the element stylesheets.
     *
     * @return array
     */
    public function getStyles()
    {
        return array(
            '@CscfaTwigUIBundle/Resources/public/css/inline_layout.css',
        );
    }
}
