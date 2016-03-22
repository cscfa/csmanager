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

use Cscfa\Bundle\TwigUIBundle\Modules\AbstractModule;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleInterface;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;

/**
 * AbstractModuleTest.
 *
 * The AbstractModuleTest is used to test the instance
 * of AbstractModule.
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
class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Module.
     *
     * This property store the AbstractModule
     * instance to be tested.
     *
     * @var AbstractModule|\PHPUnit_Framework_MockObject_MockObject
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
        $this->module = $this->getMockForAbstractClass(AbstractModule::class);
    }

    /**
     * Test store.
     *
     * This method test the ModuleInterface
     * storage process of the AbstractModule.
     */
    public function testStore()
    {
        $arr = array(
            $this->getMockForAbstractClass(ModuleInterface::class),
            $this->getMockForAbstractClass(ModuleInterface::class),
            $this->getMockForAbstractClass(ModuleInterface::class),
            $this->getMockForAbstractClass(ModuleInterface::class),
        );

        foreach ($arr as $module) {
            $this->assertSame(
                $this->module,
                $this->module->addModule($module),
                'ModuleInterface::addModule must return ModuleInterface'
            );
        }

        $this->assertInstanceOf(
            ModuleSetInterface::class,
            $this->module->getModules(),
            'ModuleInterface::getModules must return an instance of ModuleSetInterface'
        );

        $resArr = array();
        foreach ($this->module->getModules() as $module) {
            $resArr[] = $module;
        }
        $this->assertEquals(
            $arr,
            $resArr,
            'ModuleInterface::getModules must return the stored modules'
        );
    }

    /**
     * Test priority.
     *
     * This method test the priority setting
     * of the AbstractModule.
     */
    public function testPriority()
    {
        $this->assertSame(
            $this->module,
            $this->module->setPriority(50),
            'ModuleInterface::setPriority must return ModuleInterface'
        );

        $this->assertEquals(
            50,
            $this->module->getPriority(),
            'ModuleInterface::getPriority must return the stored priority'
        );
    }
}
