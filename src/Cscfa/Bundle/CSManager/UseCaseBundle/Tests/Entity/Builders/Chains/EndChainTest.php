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
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Entity\Builders\Chains;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\EndChain;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainObserverInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * EndChainTest.
 *
 * The EndChainTest
 * process the EndChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\EndChain
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * 
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\EndChain
 */
class EndChainTest extends \PHPUnit_Framework_TestCase
{

    /**
     * EndChain
     * 
     * The tested EndChain instance.
     * 
     * @var EndChain
     */
    protected $endChain;

    /**
     * EndChain
     * 
     * The tested EndChain instance that
     * is defined to not throw exception.
     * 
     * @var EndChain
     */
    protected $unthrowEndChain;

    /**
     * Set up
     * 
     * This method set up the test class
     * before tests.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $this->endChain = new EndChain();
        $this->unthrowEndChain = new EndChain(false);
    }
    
    /**
     * Test next
     * 
     * This method test the process
     * access for the next chain element.
     */
    public function testNext(){
        $next = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array("process", "setNext", "getNext", "support", "getAction"))
            ->getMock();
        $next->expects($this->once())
            ->method("process")
            ->will($this->returnSelf());
        $this->endChain->setThrowable(false)
            ->setNext($next);
        $data = null;
        $this->endChain->process(null, $data);
    }

    /**
     * Test exception
     * 
     * This method process test for the
     * exceptions throwing of the EndChain
     * instance.
     */
    public function testException(){
        $observer = $this->getMockBuilder(ChainObserverInterface::class)
            ->setMethods(array("isUsed", "getActions", "notify"))
            ->getMock();
        
        $actionsChain = array("firstAction", "secondAction", "thirdAction");
        $data = null;
        
        //Used chain
        $usedObserver = clone $observer;
        $usedObserver->expects($this->once())
            ->method("isUsed")
            ->will($this->returnValue(true));
        $usedChain = clone $this->endChain;
        $usedChain->addObserver($usedObserver);
        $usedChain->process(null, $data);
        
        //unused chain
        $unusedObserver = clone $observer;
        $unusedObserver->expects($this->once())
            ->method("isUsed")
            ->will($this->returnValue(false));
        $unusedObserver->expects($this->once())
            ->method("getActions")
            ->will($this->returnValue($actionsChain));
        $usedChain = clone $this->endChain;
        $usedChain->addObserver($usedObserver);
        
        try {
            $usedChain->process(null, $data);
            $this->fail("Unused chain must throw an exception");
        } catch (\Exception $exception) {
            $this->assertTrue(true);
        }
            
        //unused chain
        $unusedObserver = clone $observer;
        $unusedObserver->expects($this->once())
            ->method("isUsed")
            ->will($this->returnValue(false));
        $unusedObserver->expects($this->once())
            ->method("getActions")
            ->will($this->returnValue($actionsChain));
        $usedChain = clone $this->unthrowEndChain;
        $usedChain->addObserver($usedObserver);
        
        try {
            $usedChain->process(null, $data);
            $this->assertTrue(true);
        } catch (\Exception $exception) {
            $this->fail("Unused chain mustn't throw any exception if it define to not throw it");
        }
    }
    
    /**
     * Test support
     * 
     * This method test the support
     * method of the EndChain.
     */
    public function testSupport() {
        $this->assertFalse(
            $this->endChain->support($this->endChain->getAction()),
            "The EndChain mustn't support it's own action"
        );
    }
    
    /**
     * Test action
     * 
     * This method test the get action
     * result of the EndChain instance.
     */
    public function testAction(){
        $this->assertEquals(
            "endChain", 
            $this->endChain->getAction(),
            "The EndChain must return endChain as action"
        );
    }
}
