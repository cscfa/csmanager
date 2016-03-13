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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\ChainOfResponsabilities\Observers;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserverFactory;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserver;

/**
 * ChainObserverFactoryTest.
 *
 * The ChainObserverFactoryTest
 * process the ChainObserverFactory
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserverFactory
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ChainObserverFactoryTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Main factory
     * 
     * This property store
     * the ChainObserverFactory
     * for the tests.
     * 
     * @var ChainObserverFactory
     */
    protected $mainFactory;
    
    /**
     * Set up
     * 
     * This methoid set up the
     * test case before tests.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp(){
        $this->mainFactory = new ChainObserverFactory();
    }
    
    /**
     * Test get instance
     * 
     * This method test the
     * ChainObserverFactory::getInstance
     * method.
     * 
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserverFactory::getInstance
     * @return ChainObserver
     */
    public function testGetInstance(){
        
        $chainInstance = $this->mainFactory->getInstance();
        
        $this->assertInstanceOf(
            ChainObserver::class, 
            $chainInstance,
            "The ChainObserverFactory::getInstance must return an instance of ChainObserver"
        );
        
        return array($chainInstance, (clone $this->mainFactory));
    }
    
    /**
     * Test last
     * 
     * This method test the
     * last instance getter
     * of the CHainObserverFactory.
     * 
     * @param ChainObserver $lastExpected
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Observers\ChainObserverFactory::getLast
     * @depends testGetInstance
     */
    public function testLast($args){
        
        list($lastExpected, $factory) = $args;
        
        $this->assertEquals(
            $lastExpected, 
            $factory->getLast(),
            "The ChainObserverFactory::getLast must return the last created instance of ChainObserver"
        );
        
    }
    
}