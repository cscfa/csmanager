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

namespace Cscfa\Bundle\TwigUIBundle\Test\Objects;

use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\ObjectsContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Object\ControllerInformation\ControllerInfo;

/**
 * EnvironmentContainerTest.
 *
 * The EnvironmentContainerTest is used to test the
 * EnvironmentContainer.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Container.
     *
     * The tested instance of EnvironmentContainer.
     *
     * @var EnvironmentContainer
     */
    protected $container;

    /**
     * Object container.
     *
     * The ObjectContainer injected into the test
     * EnvironmentContainer instance.
     *
     * @var ObjectsContainer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectContainer;

    /**
     * Twig request.
     *
     * This property store a TwigRequestIterator
     * to test EnvironmentContainer support
     * process.
     *
     * @var TwigRequestIterator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $twigRequests;

    /**
     * Controller info.
     *
     * This property store a ControllerInfo
     * to test EnvironmentContainer support
     * process.
     *
     * @var ControllerInfo|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $controllerInfo;

    /**
     * Set up.
     *
     * This method set up the test class before
     * tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->container = new EnvironmentContainer();
        $this->objectContainer = $this->getMockBuilder(ObjectsContainer::class)
            ->getMock();

        $this->twigRequests = $this->getMockBuilder(TwigRequestIterator::class)
            ->getMock();

        $this->controllerInfo = $this->getMockBuilder(ControllerInfo::class)
            ->getMock();

        $this->container->setObjectsContainer($this->objectContainer);
        $this->container->setTwigRequests($this->twigRequests);
        $this->container->setControllerInfo($this->controllerInfo);
    }

    /**
     * Test object container.
     *
     * This method allow to test the ObjectContainer
     * support of the EnvironmentContainer class.
     */
    public function testObjectContainer()
    {
        $this->assertSame(
            $this->objectContainer,
            $this->container->getObjectsContainer(),
            'The EnvironmentContainer::getObjectContainer must return the registered ObjectContainer'
        );
    }

    /**
     * Test twig request.
     *
     * This method allow to test the ObjectContainer
     * support of the TwigRequestIterator class.
     */
    public function testTwigRequest()
    {
        $this->assertSame(
            $this->twigRequests,
            $this->container->getTwigRequests(),
            'The EnvironmentContainer::getTwigRequests must return the registered TwigRequestIterator'
        );
    }

    /**
     * Test controller info.
     *
     * This method allow to test the ObjectContainer
     * support of the ControllerInfo class.
     */
    public function testControllerInfo()
    {
        $this->assertSame(
            $this->controllerInfo,
            $this->container->getControllerInfo(),
            'The EnvironmentContainer::getControllerInfo must return the registered ControllerInfo'
        );

        $this->controllerInfo->expects($this->exactly(2))
            ->method('isImmutable')
            ->will($this->onConsecutiveCalls(true, false));

        $newCtrl = $this->getMock(ControllerInfo::class);
        $this->container->setControllerInfo($newCtrl);
        $this->assertSame(
            $this->controllerInfo,
            $this->container->getControllerInfo(),
            'The EnvironmentContainer::setControllerInfo must desallow to update the registered' +
            'ControllerInfo if it\'s immutable'
        );
        $this->container->setControllerInfo($newCtrl);
        $this->assertNotSame(
            $this->controllerInfo,
            $this->container->getControllerInfo(),
            'The EnvironmentContainer::setControllerInfo must allow to update the registered' +
            'ControllerInfo if it\'s not immutable'
        );
    }
}
