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

use Cscfa\Bundle\TwigUIBundle\Modules\ModuleSet;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleSetInterface;
use Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleInterface;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;

/**
 * ModuleSetTest.
 *
 * The ModuleSetTest is used to test the instance
 * of ModuleSet.
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
class ModuleSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set.
     *
     * This property store the ModuleSet
     * instance to test.
     *
     * @var ModuleSetInterface
     */
    protected $set;

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
        $this->set = new ModuleSet();
    }

    /**
     * Iteration provider.
     *
     * This method provide a set
     * of ModuleInterface to bu
     * used as iterable by the
     * ModuleSet.
     *
     * @return PHPUnit_Framework_MockObject_MockObject[][][]
     */
    public function iterationProvider()
    {
        return array(
            [
                [
                    $this->getMockForAbstractClass(ModuleInterface::class),
                    $this->getMockForAbstractClass(ModuleInterface::class),
                    $this->getMockForAbstractClass(ModuleInterface::class),
                    $this->getMockForAbstractClass(ModuleInterface::class),
                    $this->getMockForAbstractClass(ModuleInterface::class),
                ],
            ],
        );
    }

    /**
     * Test iterator.
     *
     * This method test the iterator
     * process of the ModuleSet.
     *
     * @param array $array The iteration data
     *
     * @dataProvider iterationProvider
     */
    public function testIterator($array)
    {
        foreach ($array as $iterable) {
            $this->set->addModule($iterable);
        }

        foreach ($this->set as $key => $moduleSetElement) {
            $this->assertSame(
                $moduleSetElement,
                $array[$key],
                'The ModuleSet must return the registered element'
            );
        }
    }

    /**
     * Test process.
     *
     * This method test the
     * process support of the
     * ModuleSet.
     */
    public function testProcess()
    {
        $first = $this->getMockForAbstractClass(ModuleInterface::class);
        $second = $this->getMockForAbstractClass(ModuleInterface::class);
        $third = $this->getMockForAbstractClass(ModuleInterface::class);

        $environment = $this->getMockBuilder(EnvironmentContainer::class)
            ->getMock();

        $first->expects($this->any())
            ->method('getPriority')
            ->will($this->returnValue(5));
        $first->expects($this->once())
            ->method('process')
            ->with($this->identicalTo($environment));

        $second->expects($this->any())
            ->method('getPriority')
            ->will($this->returnValue(10));
        $second->expects($this->once())
            ->method('process')
            ->with($this->identicalTo($environment));

        $third->expects($this->any())
            ->method('getPriority')
            ->will($this->returnValue(15));
        $third->expects($this->once())
            ->method('process')
            ->with($this->identicalTo($environment));

        $this->set->addModule($second)
            ->addModule($third)
            ->addModule($first);

        $this->set->processAll($environment);

        $this->assertSame(
            array($first, $second, $third),
            $this->set->toArray(),
            'The ModuleSet must sort the registered element sorted as it priority'
        );
    }
}
