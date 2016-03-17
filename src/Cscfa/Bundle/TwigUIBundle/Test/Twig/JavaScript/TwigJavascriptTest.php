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

namespace Cscfa\Bundle\TwigUIBundle\Test\Twig\JavaScript;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\TwigUIBundle\Element\Twig\JavaScript\TwigJavascript;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;

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
class TwigJavascriptTest extends WebTestCase
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
     * Test javascript.
     *
     * This method test
     * the TwigJavascript
     * instance.
     */
    public function testJavascript()
    {
        $javascript = new TwigJavascript();

        $this->getterEmpty($javascript);
        $this->setters($javascript);
        $this->stringify($javascript);
    }

    /**
     * Test stringify.
     *
     * This method provide
     * a test for the toString
     * method of a TwigJavascript
     * instance.
     *
     * @param TwigJavascript $javascript The TwigJavascript instance
     */
    public function stringify(TwigJavascript $javascript)
    {
        $result = "\t\t\t{% javascripts \"test.js\"\n";
        $result .= "\t\t\t\t\"script.js\" %}\n";
        $result .= "\t\t\t\t<script src=\"{{ asset_url }}\">\n";
        $result .= "\t\t\t\t</script>\n";
        $result .= "\n";
        $result .= "\t\t\t\t<script>\n";
        $result .= "\t\t\t\t\t\n";
        $result .= "\t\t\t\t\ttest = function(){\n";
        $result .= "\t\t\t\t\t}\n";
        $result .= "\t\t\t\t\t\n";
        $result .= "\t\t\t\t\ttest.prototype.print = function(){\n";
        $result .= "\t\t\t\t\t\tconsole.log(\"hello\");\n";
        $result .= "\t\t\t\t\t}\n";
        $result .= "\t\t\t\t\t\n";
        $result .= "\t\t\t\t\tvar tester = new test();\n";
        $result .= "\t\t\t\t\ttester.print();\n";
        $result .= "\t\t\t\t</script>\n";
        $result .= "\n";
        $result .= "\t\t\t{% endjavascripts %}";

        $this->assertTrue($javascript->__toString() == $result);
    }

    /**
     * Test getters.
     *
     * This method provide tests
     * for TwigJavascript instance.
     *
     * @param TwigJavascript $javascript The TwigJavascript instance
     * @param string         $script     A script that was setted
     */
    public function getters(TwigJavascript $javascript, $script)
    {
        $this->assertTrue($javascript->getNestedLevel() == 3);
        $this->assertTrue($javascript->getScriptLinked()->contain('test.js'));
        $this->assertTrue($javascript->getScriptLinked()->contain('script.js'));
        $this->assertFalse($javascript->getScriptLinked()->contain('hello.js'));

        $this->assertTrue($javascript->getScriptText()->contain($script));
    }

    /**
     * Test setters.
     *
     * This method provide test
     * for setters methods of
     * TwigJavascript instance.
     *
     * @param TwigJavascript &$js The TwigJavascript instance.
     */
    public function setters(TwigJavascript &$javascript)
    {
        $nestedLevel = 3;
        $scriptLinked = new ListSet();
        $scriptText = new ListSet();

        $javascript->setScriptLinked($scriptLinked);
        $javascript->setScriptText($scriptText);

        $this->getterEmpty($javascript);

        $javascript->setNestedLevel($nestedLevel);
        $javascript->addScriptLinked('test.js');
        $javascript->addScriptLinked('script.js');

        $script = "test = function(){\n}\n";
        $script .= "\ntest.prototype.print = function(){\n";
        $script .= "\tconsole.log(\"hello\");\n}\n";
        $script .= "\nvar tester = new test();\ntester.print();";

        $javascript->addScriptText($script);
    }

    /**
     * Getter empty.
     *
     * This method provide test
     * for empty instance of
     * TwigJavascript.
     *
     * @param TwigJavascript $javascript The empty instance to test.
     */
    public function getterEmpty(TwigJavascript $javascript)
    {
        $this->assertTrue($javascript->getNestedLevel() == 0);
        $this->assertTrue($javascript->getScriptLinked() instanceof ListSet);
        $this->assertTrue($javascript->getScriptText() instanceof ListSet);

        $this->assertTrue($javascript->getScriptLinked()->isEmpty());
        $this->assertTrue($javascript->getScriptText()->isEmpty());

        $this->assertTrue(is_array($javascript->getSubTags()));
        $this->assertTrue(empty($javascript->getSubTags()));
        $this->assertTrue($javascript->getSubTagsCount() == 0);
    }
}
