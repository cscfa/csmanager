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

use Cscfa\Bundle\TwigUIBundle\Modules\AbstractStepModule;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy\TwigHierarchy;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;

/**
 * AbstractStepModuleTest.
 *
 * The AbstractStepModuleTest is used to test the instance
 * of AbstractStepModule.
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
class AbstractStepModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Module.
     *
     * This property store the AbstractTopLevelModule
     * instance to be tested.
     *
     * @var AbstractStepModule|\PHPUnit_Framework_MockObject_MockObject
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
        $this->module = $this->getMockForAbstractClass(AbstractStepModule::class);
    }

    /**
     * Test process.
     *
     * This method test the processing
     * of the AbstractStepModule
     * instance.
     */
    public function testProcess()
    {
        $request = new TwigRequest();

        $twigHierarchy = $this->getMockBuilder(TwigHierarchy::class)
            ->setMethods(array('downStep', 'register'))
            ->getMock();

        $twigHierarchy->expects($this->once())
            ->method('downStep')
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
