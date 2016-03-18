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

namespace Cscfa\Bundle\TwigUIBundle\Test\Objects\ControllerInformation;

use Cscfa\Bundle\TwigUIBundle\Object\ControllerInformation\ControllerInfo;

/**
 * ControllerInfoTest.
 *
 * The ControllerInfoTest is used to test the
 * ControllerInfo.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ControllerInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Controller info.
     *
     * This property register the ControllerInfo
     * instance to test.
     *
     * @var ControllerInfo
     */
    protected $controllerInfo;

    /**
     * Set up.
     *
     * This method set up the test class
     * before tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->controllerInfo = new ControllerInfo();
    }

    /**
     * Type provider.
     *
     * This method provide several type
     * data to be used in test.
     *
     * @return array
     */
    public function typeProvider()
    {
        return array(
            array(true, false),
            array(12, false),
            array(24.50, false),
            array(new \stdClass(), false),
            array('string', true),
        );
    }

    /**
     * Test type.
     *
     * This method test the type error
     * support of the ControllerInfo method
     * name and controller name.
     *
     * @param string $dataTest    The data provided
     * @param bool   $noException The exception expecting
     *
     * @dataProvider typeProvider
     */
    public function testType($dataTest, $noException)
    {
        if ($noException) {
            $this->controllerInfo->setControllerName($dataTest);
            $this->controllerInfo->setMethodName($dataTest);
        } else {
            try {
                $this->controllerInfo->setControllerName($dataTest);
                $this->fail(
                    'The ControllerInfo::setControllerName must thrown exception if data is not string'
                );
            } catch (\Exception $e) {
                $this->assertEquals(
                    500,
                    $e->getCode(),
                    'The ControllerInfo::setControllerName must thrown exception with code 500 if data' +
                    'is not string'
                );
            }

            try {
                $this->controllerInfo->setMethodName($dataTest);
                $this->fail(
                    'The ControllerInfo::setMethodName must thrown exception if data is not string'
                );
            } catch (\Exception $e) {
                $this->assertEquals(
                    500,
                    $e->getCode(),
                    'The ControllerInfo::setMethodName must thrown exception with code 500 if data' +
                    'is not string'
                );
            }
        }
    }

    /**
     * Test mutable.
     *
     * This method test the ControllerInfo
     * into a mutable state.
     */
    public function testMutable()
    {
        $controllerName = 'controller';
        $methodName = 'method';

        $this->assertSame(
            $this->controllerInfo,
            $this->controllerInfo->setControllerName($controllerName),
            'The ControllerInfo::setControllerName must return itself'
        );
        $this->assertSame(
            $this->controllerInfo,
            $this->controllerInfo->setMethodName($methodName),
            'The ControllerInfo::setMethodName must return itself'
        );

        $this->assertEquals(
            $controllerName,
            $this->controllerInfo->getControllerName(),
            'The ControllerInfo::getControllerName must return the registered controller name'
        );
        $this->assertEquals(
            $methodName,
            $this->controllerInfo->getMethodName(),
            'The ControllerInfo::getMethodName must return the registered method name'
        );
    }

    /**
     * Test mutable.
     *
     * This method test the ControllerInfo
     * into an immutable state.
     */
    public function testimmutable()
    {
        $this->assertSame(
            $this->controllerInfo,
            $this->controllerInfo->setImmutable(true),
            'The ControllerInfo::setImmutable must return itself'
        );
        $this->controllerInfo->setControllerName('controller');
        $this->controllerInfo->setMethodName('method');
        $this->controllerInfo->setImmutable(false);

        $this->assertEmpty(
            $this->controllerInfo->getControllerName(),
            'The ControllerInfo::setControllerName cannot registered method name if immutable'
        );
        $this->assertEmpty(
            $this->controllerInfo->getMethodName(),
            'The ControllerInfo::setMethodName cannot registered registered method name if immutable'
        );
        $this->assertTrue(
            $this->controllerInfo->isImmutable(),
            'The ControllerInfo::setImmutable cannot change the immutable state if already immutable'
        );
    }
}
