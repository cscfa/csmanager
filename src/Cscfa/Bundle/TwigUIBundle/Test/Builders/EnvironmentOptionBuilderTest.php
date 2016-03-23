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

namespace Cscfa\Bundle\TwigUIBundle\Test\Builders;

use Cscfa\Bundle\TwigUIBundle\Builders\EnvironmentOptionBuilder;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * EnvironmentOptionBuilderTest.
 *
 * The EnvironmentOptionBuilderTest is used to test the
 * EnvironmentOptionBuilder.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentOptionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Builder.
     *
     * This property store the
     * EnvironmentOptionBuilder
     * instance to test.
     *
     * @var EnvironmentOptionBuilder
     */
    protected $builder;

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
        $this->builder = new EnvironmentOptionBuilder();
    }

    /**
     * Test building.
     *
     * This method test the process
     * of the EnvironmentOptionBuilder.
     */
    public function testBuilding()
    {
        $expected = array(
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
        );

        $this->assertSame(
            $this->builder,
            $this->builder->addOption(
                EnvironmentOptionBuilder::OBJECT_CONTAINER_OBJECT,
                array(new \stdClass(), 'standardClass')
            ),
            'EnvironmentOptionBuilder::addOption must return EnvironmentOptionBuilder'
        );

        $this->builder->addOption(
            EnvironmentOptionBuilder::CONTROLLER_INFO_CONTROLLER,
            'myAwesomeController'
        )->addOption(
            EnvironmentOptionBuilder::CONTROLLER_INFO_METHOD,
            'foo'
        )->addOption(
            EnvironmentOptionBuilder::TWIG_REQUEST_TWIG_REQUEST,
            array(new TwigRequest('path', []), 'theRequestAlias')
        )->addOption(
            EnvironmentOptionBuilder::TWIG_REQUEST_TWIG_REQUEST,
            array(new TwigRequest('secondPath', []), 'theAlias')
        );

        $this->assertEquals(
            $expected,
            $this->builder->getOption(),
            'The options returned by the builder must return the processed options'
        );
    }
}
