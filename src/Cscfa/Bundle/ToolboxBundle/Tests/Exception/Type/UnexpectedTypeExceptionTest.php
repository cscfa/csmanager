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

namespace Cscfa\Bundle\ToolboxBundle\Tests\Exception\Type;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Exception\Type\UnexpectedTypeException;

/**
 * UnexpectedTypeExceptionTest class.
 *
 * The UnexpectedTypeExceptionTest class provide
 * test to valid UnexpectedTypeException methods
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
class UnexpectedTypeExceptionTest extends WebTestCase
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
     * the UnexpectedTypeException constructor.
     */
    public function testConstructor()
    {
        $allowed = array(
            'boolean',
            'string',
        );

        $exception = new UnexpectedTypeException('type exception', 404, null, $allowed);

        $this->assertTrue($exception->getMessage() == "type exception\n Allowed types : boolean, string");
    }
}
