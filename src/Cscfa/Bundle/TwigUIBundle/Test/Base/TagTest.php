<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Test\Base;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;
use Cscfa\Bundle\ToolboxBundle\Set\HackSet;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\TwigUIBundle\Element\Base\Attr\MultipleAttr;

/**
 * TagTest class.
 *
 * The TagTest class provide
 * test to valid Tags classes.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class TagTest extends WebTestCase
{
    /**
     * The setUp.
     *
     * This method is used to configure
     * and process the initialisation of
     * the test class.
     */
    public function setUp()
    {
    }

    /**
     * Test tag.
     *
     * This method give test
     * suite for Tag class.
     */
    public function testTag()
    {
        $tag = new Tag();

        $this->getterEmpty($tag);
        $this->setters($tag);
        $this->stringify($tag);
    }

    /**
     * Test stringify.
     *
     * This method test the
     * __toString methods of a
     * Tag instance.
     *
     * @param Tag $tag The tag instance
     */
    public function stringify(Tag $tag)
    {
        $string = "\t\t\t<p id=\"ig_test\" class=\"class_test\" src=\"/test.css\">\n";
        $string .= "\t\t\t\t<strong>\n";
        $string .= "\t\t\t\t</strong>\n";
        $string .= "\t\t\t</p>\n";

        $this->assertTrue($tag->__toString() == $string);
    }

    /**
     * Test setters.
     *
     * This method test the
     * setters methods of a
     * Tag instance.
     *
     * @param Tag &$tag The tag instance
     */
    public function setters(Tag &$tag)
    {
        $child = new Tag('strong');
        $attributes = new HackSet();
        $childs = new ListSet();
        $class = new MultipleAttr();
        $tagId = 'ig_test';
        $inline = true;
        $nestedLevel = 3;
        $tagName = 'p';

        $tag->setAttributes($attributes);
        $tag->setChilds($childs);
        $tag->setClass($class);

        $this->getterEmpty($tag);

        $tag->getAttributes()->add('src', '/test.css');
        $tag->getClass()->add('class_test');
        $tag->setId($tagId);
        $tag->setInline($inline);

        $tag->setNestedLevel($nestedLevel);
        $tag->setTagName($tagName);
        $tag->addChild($child);

        $this->getters($tag, $child);
    }

    /**
     * Test getters.
     *
     * This method test the
     * getters methods of a
     * not empty Tag instance.
     *
     * @param Tag $tag   The tag instance
     * @param Tag $child The child tag instance
     */
    public function getters(Tag $tag, Tag $child)
    {
        $this->assertTrue($tag->getAttributes()->hasKey('src'));
        $this->assertTrue(in_array('/test.css', $tag->getAttributes()->get('src')));

        $this->assertTrue($tag->getClass()->contain('class_test'));

        $this->assertTrue($tag->getId() == 'ig_test');
        $this->assertTrue($tag->isInline());
        $this->assertTrue($tag->getNestedLevel() == 3);
        $this->assertTrue($tag->getTagName() == 'p');

        $this->assertTrue($tag->getChilds()->contain($child));
    }

    /**
     * Test getters.
     *
     * This method test the
     * getters methods of an
     * empty Tag instance.
     *
     * @param Tag $tag The empty tag instance
     */
    public function getterEmpty(Tag $tag)
    {
        $this->assertTrue($tag->getAttributes() instanceof HackSet);
        $this->assertTrue($tag->getChilds() instanceof ListSet);
        $this->assertTrue($tag->getClass() instanceof MultipleAttr);
        $this->assertTrue($tag->getId() == '');
        $this->assertTrue(is_integer($tag->getNestedLevel()));
        $this->assertTrue($tag->getNestedLevel() == 0);

        $this->assertTrue(is_array($tag->getSubTags()));
        $this->assertTrue(empty($tag->getSubTags()));
        $this->assertTrue(is_integer($tag->getSubTagsCount()));
        $this->assertTrue($tag->getSubTagsCount() == 0);

        $this->assertTrue($tag->getTagName() == '');
        $this->assertFalse($tag->isInline());
    }
}
