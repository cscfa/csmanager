<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\ToolboxBundle\Tests\Factory\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Factory\Command\ColoredStringFactory;

/**
 * ColoredStringFactoryTest class.
 *
 * The ColoredStringFactoryTest class provide 
 * test to valid ColoredStringFactory methods 
 * process.
 *
 * @category Test
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class ColoredStringFactoryTest extends WebTestCase
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
     * The testConstructor test.
     *
     * This test is used to confirm
     * the ColoredStringFactory constructor.
     *
     * @return void
     */
    public function testConstructor()
    {
        $factory = new ColoredStringFactory(null, null, null, "text");
        
        $this->assertTrue($factory->getBackground() === null);
        $this->assertTrue($factory->getForeground() === null);
        $this->assertTrue($factory->getOption() === null);
        $this->assertTrue($factory->getText() == "text");
        $this->assertTrue($factory->getString() == "text");

        $factory = new ColoredStringFactory(
            ColoredStringFactory::BLACK, 
            ColoredStringFactory::BLUE, 
            ColoredStringFactory::BOLD, 
            "text"
        );
        
        $this->assertTrue($factory->getBackground() === ColoredStringFactory::BLUE);
        $this->assertTrue($factory->getForeground() === ColoredStringFactory::BLACK);
        $this->assertTrue($factory->getOption() === ColoredStringFactory::BOLD);
        $this->assertTrue($factory->getText() == "text");
        $this->assertTrue($factory->getString() == "<fg=black;bg=blue;options=bold>text</fg=black;bg=blue;options=bold>");
    }

    /**
     * The testSetters test.
     *
     * This test is used to confirm
     * the ColoredStringFactory setters.
     *
     * @return void
     */
    public function testSetters()
    {
        $factory = new ColoredStringFactory();
        
        $factory->setBackground($factory::BLUE);
        $factory->setForeground($factory::BLACK);
        $factory->setOption($factory::BOLD);
        $factory->setText("text");

        $this->assertTrue($factory->getBackground() === ColoredStringFactory::BLUE);
        $this->assertTrue($factory->getForeground() === ColoredStringFactory::BLACK);
        $this->assertTrue($factory->getOption() === ColoredStringFactory::BOLD);
        $this->assertTrue($factory->getText() == "text");
        $this->assertTrue($factory->getString() == "<fg=black;bg=blue;options=bold>text</fg=black;bg=blue;options=bold>");
    }
}
