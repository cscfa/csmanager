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

namespace Cscfa\Bundle\ToolboxBundle\Tests\Converter\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Converter\Command\CommandTypeConverter;

/**
 * CommandTypeConverterTest class.
 *
 * The CommandTypeConverterTest class provide
 * test to valid CommandTypeConverter methods
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
class CommandTypeConverterTest extends WebTestCase
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
     * The testConversion test.
     *
     * This test is used to confirm
     * the CommandTypeConverter conversion.
     */
    public function testConversion()
    {
        $array = array('1', '2', '3');
        $arrayConverted = '1, 2, 3';

        $dateTimeConverted = '2015-04-13 10:10:24';
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeConverted);

        $this->assertTrue(CommandTypeConverter::convertToString($array) == $arrayConverted);
        $this->assertTrue(CommandTypeConverter::convertToString($dateTime) == $dateTimeConverted);
        $this->assertTrue(CommandTypeConverter::convertToString(null) == 'NULL');
        $this->assertTrue(CommandTypeConverter::convertToString(true) == 'true');
        $this->assertTrue(CommandTypeConverter::convertToString(false) == 'false');
    }
}
