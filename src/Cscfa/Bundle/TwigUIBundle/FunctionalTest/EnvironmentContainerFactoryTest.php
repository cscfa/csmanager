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

namespace Cscfa\Bundle\TwigUIBundle\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\TwigUIBundle\Factories\EnvironmentFactory;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * EnvironmentContainerFactoryTest.
 *
 * The EnvironmentContainerFactoryTest is used to test
 * the EnvironmentContainerFactory service.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @SuppressWarnings(PHPMD)
 */
class EnvironmentContainerFactoryTest extends WebTestCase
{
    /**
     * Factory.
     *
     * This property store the
     * EnvironmentFactory instance
     * to test.
     *
     * @var EnvironmentFactory
     */
    protected $factory;

    /**
     * Set up.
     *
     * This method set up the test
     * class before tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        self::bootKernel();

        $this->factory = static::$kernel->getContainer()->get('EnvironmentContainerFactory');
    }

    /**
     * Test get instance.
     *
     * This method allow to test
     * the creation of an instance
     * with the EnvironmentContainerFactory
     * service.
     */
    public function testGetInstance()
    {
        $container = $this->factory->getInstance(
            array(
                'ObjectContainer' => array(
                    ['object' => array(new \stdClass(), 'standardClass')],
                ),
                'ControllerInfo' => array(
                    ['controllerName' => 'myAwesomeController'],
                    ['methodName' => 'foo'],
                ),
                'TwigRequests' => array(
                    ['twigRequest' => array(new TwigRequest('path', []), 'theRequestAlias')],
                    ['twigRequest' => array(new TwigRequest('secondPath', []), 'theAlias')],
                ),
            )
        );

        $this->assertEquals(
            'myAwesomeController',
            $container->getControllerInfo()->getControllerName(),
            'The ControllerInfo controller name must be equal to the given option'
        );
        $this->assertEquals(
            'foo',
            $container->getControllerInfo()->getMethodName(),
            'The ControllerInfo method name must be equal to the given option'
        );

        $this->assertTrue(
            $container->getObjectsContainer()->hasObject('standardClass'),
            'The ObjectContainer must contain the data given with the options option'
        );
        $this->assertEquals(
            new \stdClass(),
            $container->getObjectsContainer()->getObject('standardClass'),
            'The ObjectContainer must contain the data given with the options option'
        );

        $expected = array(
            'theRequestAlias' => new TwigRequest('path', []),
            'theAlias' => new TwigRequest('secondPath', []),
        );
        $current = array();
        foreach ($container->getTwigRequests() as $key => $request) {
            $current[$key] = $request;
        }
        $this->assertEquals(
            $expected,
            $current,
            'The TwigRequestIterator must contain the data given with the options option'
        );

        $this->assertSame(
            $container->getTwigRequests(),
            $container->getTwigHierarchy()->getMainRegistry(),
            'The TwigHierarchy must register the TwigRequest as main registry'
        );
    }
}
