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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\ChainOfResponsabilities\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain;
use Cscfa\Bundle\CSManager\UseCaseBundle\Observer\Interfaces\ObserverInterface;

/**
 * AbstractChainTest.
 *
 * The AbstractChainTest
 * process the AbstractChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AbstractChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Main chain.
     *
     * This property register a
     * default mock object of type
     * AbstractChain to be used
     * into test methods.
     *
     * @var \PHPUnit_Framework_MockObject_MockObject|AbstractChain
     */
    protected $mainChain;

    /**
     * Get mock chain.
     *
     * This method return a new
     * mock object of type
     * AbstractChain
     *
     * @return PHPUnit_Framework_MockObject_MockObject|AbstractChain
     */
    public function getmockChain()
    {
        return $this->getMockForAbstractClass(AbstractChain::class);
    }

    /**
     * Set up.
     *
     * This method setup the
     * test class before
     * tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->mainChain = $this->getmockChain();
    }

    /**
     * Test next element.
     *
     * This method process test
     * for AbstractChain to validate
     * the chain registration.
     *
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain::setNext
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain::getNext
     */
    public function testNextElement()
    {
        $temporaryChain = $this->getmockChain();

        $this->assertEquals(
            $this->mainChain,
            $this->mainChain->setNext($temporaryChain),
            'AbstractChain::setNext must return the AbstractChain'
        );

        $this->assertEquals(
            $temporaryChain,
            $this->mainChain->getNext(),
            'AbstractChain::getNext must return the registered AbstractChain'
        );
    }

    /**
     * Create mock observer.
     *
     * This method create a mock
     * object to simulate an instance
     * of ObserverInterface.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ObserverInterface
     */
    public function createMockObserver()
    {
        $stub = $this->getMockBuilder(ObserverInterface::class)
            ->setMethods(array('notify'))
            ->getMock();

        return $stub;
    }

    /**
     * Test observer methods.
     *
     * This method allow to test the
     * ObservableInterface implmented
     * methods of the AbstractChain.
     *
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain::addObserver
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain::getObserver
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain::notifyAll
     */
    public function testObserver()
    {
        $extraNotifyedData = array('foo' => 'bar');

        $observerA = $this->createMockObserver();
        $observerA->expects($this->once())
            ->method('notify')
            ->will($this->returnSelf())
            ->with(
                $this->equalTo($this->mainChain),
                $this->equalTo($extraNotifyedData)
            );

        $observerB = $this->createMockObserver();
        $observerB->expects($this->once())
            ->method('notify')
            ->will($this->returnSelf())
            ->with(
                $this->equalTo($this->mainChain),
                $this->equalTo($extraNotifyedData)
            );

        /*
         *  addObserver test part
         */
        $this->assertEquals(
            $this->mainChain,
            $this->mainChain->addObserver($observerA),
            'AbstractChain::addObserver must return the AbstractChain'
        );
        $this->mainChain->addObserver($observerB);

        /*
         *  getObserver test part
         */
        $registeredObserves = $this->mainChain->getObserver();
        $this->assertCount(
            2,
            $registeredObserves,
            'AbstractChain::getObserver must return each of registered observers'
        );
        $this->assertContains(
            $observerA,
            $registeredObserves,
            'AbstractChain::getObserver does not return the first registered observer'
        );
        $this->assertContains(
            $observerB,
            $registeredObserves,
            'AbstractChain::getObserver does not return the second registered observer'
        );

        /*
         *  notifyAll test part
         */
        $this->assertEquals(
            $this->mainChain,
            $this->mainChain->notifyAll($extraNotifyedData),
            'AbstractChain::addObserver must return the AbstractChain'
        );
    }
}
