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

namespace Cscfa\Bundle\TwigUIBundle\Test\Modules;

use Cscfa\Bundle\TwigUIBundle\Modules\AbstractTopLevelModule;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy\TwigHierarchy;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * AbstractTopLevelModuleTest.
 *
 * The AbstractTopLevelModuleTest is used to test the instance
 * of AbstractTopLevelModule.
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
class AbstractTopLevelModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Module.
     *
     * This property store the AbstractTopLevelModule
     * instance to be tested.
     *
     * @var AbstractTopLevelModule|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $module;

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
        $this->module = $this->getMockForAbstractClass(AbstractTopLevelModule::class);
    }

    /**
     * Test process.
     *
     * This method test the processing
     * of the AbstractTopLevelModule
     * instance.
     */
    public function testProcess()
    {
        $request = new TwigRequest();

        $twigHierarchy = $this->getMockBuilder(TwigHierarchy::class)
            ->setMethods(array('startHierarchy', 'register'))
            ->getMock();

        $twigHierarchy->expects($this->once())
            ->method('startHierarchy')
            ->will($this->returnSelf());

        $twigHierarchy->expects($this->once())
            ->method('register')
            ->with($this->identicalTo($request), $this->equalTo('topLevel'))
            ->will($this->returnSelf());

        $environment = new EnvironmentContainer();
        $environment->setTwigHierarchy($twigHierarchy);

        $this->module->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('topLevel'));

        $this->module->expects($this->once())
            ->method('render')
            ->with($this->identicalTo($environment))
            ->will($this->returnValue($request));

        $this->module->process($environment);
    }
}
