<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\ChainOfResponsabilities\Observers;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver;
use Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockInterfaces\ChainObservableInterface;

/**
 * ChainObserverTest.
 *
 * The ChainObserverTest
 * process the ChainObserver
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ChainObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create chain.
     *
     * This method create a mocked
     * chain element to be used
     * with Observer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ChainObservableInterface
     */
    protected function createChain()
    {
        $stub = $this->getMockBuilder(ChainObservableInterface::class)
            ->setMethods(
                array(
                    'setNext',
                    'getNext',
                    'process',
                    'support',
                    'getAction',
                    'addObserver',
                    'getObserver',
                    'notifyAll',
                )
            )->getMock();

        return $stub;
    }

    /**
     * Test constructor.
     *
     * This method test the
     * chain observer constructor
     * without arguments.
     */
    public function testConstruct()
    {
        $mainObserver = new ChainObserver();

        $this->assertEmpty(
            $mainObserver->getActions(),
            'ChainObserver::actions must be empty on setup'
        );

        $this->assertFalse(
            $mainObserver->isUsed(),
            'ChainObserver::used must be false on setup'
        );

        return $mainObserver;
    }

    /**
     * Test notify.
     *
     * This method test the
     * ChainObserver notification
     * process
     *
     * @param ChainObserver $mainObserver The tested instance
     * @depends testConstruct
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver::notify
     */
    public function testNotify(ChainObserver $mainObserver)
    {
        $stub = $this->createChain();

        $stub->expects($this->exactly(3))
            ->method('getAction')
            ->will($this->onConsecutiveCalls('test', 'testUnused', 'testUsed'));

        $mainObserver->notify($stub);
        $mainObserver->notify($stub, array('state' => false));

        try {
            $unusedObserver = clone $mainObserver;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $mainObserver->notify($stub, array('state' => true));

        return array($mainObserver, $unusedObserver);
    }

    /**
     * Test used.
     *
     * This method test the
     * ChainObserver use
     * process.
     *
     * @param array $args The depending arguments
     * @depends testNotify
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver::isUsed
     */
    public function testUsed($args)
    {
        list($mainObserver, $unusedObserver) = $args;

        $this->assertTrue(
            $mainObserver->isUsed(),
            'The mainObserver must be used on third notify'
        );
        $this->assertFalse(
            $unusedObserver->isUsed(),
            'The unusedObserver must not be used'
        );
    }

    /**
     * Test actions.
     *
     * This method test the
     * ChainObserver actions
     * process.
     *
     * @param array $args The depending arguments
     * @depends testNotify
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver::getActions
     */
    public function testActions($args)
    {
        list($mainObserver, $unusedObserver) = $args;

        $unusedArr = array('test', 'testUnused');
        $mainArr = array('test', 'testUnused', 'testUsed');

        $this->assertEquals(
            $unusedArr,
            $unusedObserver->getActions(),
            "The unusedObserver must contain ['test', 'testUnused'] as actions"
        );
        $this->assertEquals(
            $mainArr,
            $mainObserver->getActions(),
            "The mainObserver must contain ['test', 'testUnused', 'testUsed'] as actions"
        );
    }
}
