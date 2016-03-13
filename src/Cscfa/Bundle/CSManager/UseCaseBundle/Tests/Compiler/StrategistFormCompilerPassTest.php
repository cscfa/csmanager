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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cscfa\Bundle\CSManager\UseCaseBundle\Compiler\StrategistFormCompilerPass;
use Symfony\Component\DependencyInjection\Definition;

/**
 * StrategistFormCompilerPassTest.
 *
 * The StrategistFormCompilerPassTest
 * process the StrategistFormCompilerPass
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Compiler\StrategistFormCompilerPass
 * @category Test
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class StrategistFormCompilerPassTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Container builder
     * 
     * This property register the
     * mocked container builder
     * to be used by the compiler.
     * 
     * @var ContainerBuilder
     */
    protected $containertBuilder;
    
    /**
     * Compiler
     * 
     * This property register the
     * tested compiler instance.
     * 
     * @var StrategistFormCompilerPass
     */
    protected $compiler;
    
    /**
     * Set up
     * 
     * This method set up
     * the properties
     * before tests.
     * 
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp(){
        $stub = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(array(
                "getParameter",
                "findTaggedServiceIds",
                "getDefinition"
                )
            )->getMock();
        
        $getParameterArray = array(
            "strategy_factory"=>array(
                    "tag_name"=>"strategy",
                    "target_tag"=>"target",
                    "method"=>"add"
            )
        );
            
        $stub->expects($this->once())
            ->method("getParameter")
            ->will($this->returnValue($getParameterArray));
            
        $findFirst = array(
            "serviceId1"=>array(
                "tags"=>array(
                    "target"=>"targeted"
                )
            )
        );
        
        $findSecond = array(
            "serviceId2"=>array(
                "tags"=>array(
                    "alias"=>"aliased"
                )
            )
        );
            
        $stub->expects($this->exactly(2))
            ->method("findTaggedServiceIds")
            ->will($this->onConsecutiveCalls($findFirst, $findSecond));
        
        $stub->expects($this->any())
            ->method("getDefinition")
            ->will($this->returnValue(new Definition()));
        
        $this->compiler = new StrategistFormCompilerPass();
        $this->containertBuilder = $stub;
    }
    
    /**
     * Test process
     * 
     * This method test the
     * StrategistFormCompilerPass
     * 
     * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Compiler\StrategistFormCompilerPass
     */
    public function testProcess(){
        
        $this->compiler->process($this->containertBuilder);
        
    }
    
}
