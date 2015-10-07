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
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
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
 * @package  CscfaTwigUiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * 
     * @return void
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
     * 
     * @return void
     */
    public function testJavascript()
    {
        $js = new TwigJavascript();
        
        $this->getterEmpty($js);
        $this->setters($js);
        $this->stringify($js);
    }

    /**
     * Test stringify.
     * 
     * This method provide
     * a test for the toString
     * method of a TwigJavascript
     * instance.
     * 
     * @param TwigJavascript $js The TwigJavascript instance
     * 
     * @return void
     */
    public function stringify(TwigJavascript $js)
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
        
        $this->assertTrue($js->__toString() == $result);
    }

    /**
     * Test getters.
     * 
     * This method provide tests
     * for TwigJavascript instance.
     * 
     * @param TwigJavascript $js     The TwigJavascript instance
     * @param string         $script A script that was setted
     * 
     * @return void
     */
    public function getters(TwigJavascript $js, $script)
    {
        $this->assertTrue($js->getNestedLevel() == 3);
        $this->assertTrue($js->getScriptLinked()->contain("test.js"));
        $this->assertTrue($js->getScriptLinked()->contain("script.js"));
        $this->assertFalse($js->getScriptLinked()->contain("hello.js"));
        
        $this->assertTrue($js->getScriptText()->contain($script));
    }

    /**
     * Test setters.
     * 
     * This method provide test
     * for setters methods of
     * TwigJavascript instance.
     * 
     * @param TwigJavascript &$js The TwigJavascript instance.
     * 
     * @return void
     */
    public function setters(TwigJavascript &$js)
    {
        $nestedLevel = 3;
        $scriptLinked = new ListSet();
        $scriptText = new ListSet();
        
        $js->setScriptLinked($scriptLinked);
        $js->setScriptText($scriptText);
        
        $this->getterEmpty($js);
        
        $js->setNestedLevel($nestedLevel);
        $js->addScriptLinked("test.js");
        $js->addScriptLinked("script.js");
        
        $script = "test = function(){\n}\n";
        $script .= "\ntest.prototype.print = function(){\n";
        $script .= "\tconsole.log(\"hello\");\n}\n";
        $script .= "\nvar tester = new test();\ntester.print();";
        
        $js->addScriptText($script);
    }

    /**
     * Getter empty.
     * 
     * This method provide test
     * for empty instance of
     * TwigJavascript.
     * 
     * @param TwigJavascript $js The empty instance to test.
     * 
     * @return void
     */
    public function getterEmpty(TwigJavascript $js)
    {
        $this->assertTrue($js->getNestedLevel() == 0);
        $this->assertTrue($js->getScriptLinked() instanceof ListSet);
        $this->assertTrue($js->getScriptText() instanceof ListSet);
        
        $this->assertTrue($js->getScriptLinked()->isEmpty());
        $this->assertTrue($js->getScriptText()->isEmpty());
        
        $this->assertTrue(is_array($js->getSubTags()));
        $this->assertTrue(empty($js->getSubTags()));
        $this->assertTrue($js->getSubTagsCount() == 0);
    }
}
