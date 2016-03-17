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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Tests\Builder\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;

/**
 * CommandTableBuilderTest class.
 *
 * The CommandTableBuilderTest class provide
 * test to valid CommandTableBuilder methods
 * process.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class CommandTableBuilderTest extends WebTestCase
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
     * The testConstructor test.
     *
     * This test is used to confirm
     * the CommandTableBuilder constructor.
     */
    public function testConstructor()
    {
        $builder = new CommandTableBuilder();

        $this->assertTrue($builder->getValues() == array());
        $this->assertTrue($builder->getKeys() == array());
        $this->assertTrue($builder->getType() == CommandTableBuilder::TYPE_ARRAY);

        $type = CommandTableBuilder::TYPE_ARRAY;
        $values = array(array(11, 12), array(21, 22));
        $keys = array('first' => 0, 'second' => 1);

        $builder = new CommandTableBuilder($type, $values, $keys);
        $this->assertTrue($builder->getValues() == $values);
        $this->assertTrue($builder->getKeys() == $keys);
        $this->assertTrue($builder->getType() == $type);

        $this->assertTrue($builder->getHeader() == array('first', 'second'));
        $this->assertTrue($builder->getRows() == array(array(11, 12), array(21, 22)));
    }

    /**
     * The testSetter test.
     *
     * This test is used to confirm
     * the CommandTableBuilder setters.
     */
    public function testSetter()
    {
        $type = CommandTableBuilder::TYPE_ARRAY;
        $values = array(array(11, 12, 13), array(21, 22, 23));
        $keys = array('first' => 0, 'third' => 2);

        $builder = new CommandTableBuilder();
        $builder->setType($type);
        $builder->setKeys($keys);
        $builder->setValues($values);

        $this->assertTrue($builder->getValues() == $values);
        $this->assertTrue($builder->getKeys() == $keys);
        $this->assertTrue($builder->getType() == $type);

        $this->assertTrue($builder->getHeader() == array('first', 'third'));
        $this->assertTrue($builder->getRows() == array(array(11, 13), array(21, 23)));
    }
}
