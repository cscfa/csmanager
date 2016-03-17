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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Entity\Builders\Chains;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddDataChain;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockObject\AddDataChainTestObject;

/**
 * AddDataChainTest.
 *
 * The AddDataChainTest
 * process the AddDataChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddDataChain
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddDataChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * AddDataChain.
     *
     * This method register the
     * tested AddDataChain
     *
     * @var AddDataChain
     */
    protected $addDataChain;

    const SUPPORTED_PROPERTY_NAME = 'testProperty';
    const UNSUPPORTED_PROPERTY_NAME = 'unsupportProperty';

    /**
     * Set up.
     *
     * This method set up
     * the test class before
     * tests.
     */
    public function setUp()
    {
        $this->addDataChain = new AddDataChain();
        $this->addDataChain->setProperty(self::SUPPORTED_PROPERTY_NAME);
    }

    /**
     * Test process.
     *
     * This method test the
     * process method of the
     * AddDataChain.
     */
    public function testProcess()
    {
        $next = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array('setNext', 'getNext', 'process', 'support', 'getAction'))
            ->getMock();

        $next->expects($this->exactly(3))
            ->method('process')
            ->will($this->returnSelf());

        //unsuported action with array without next
        $data = array();
        $this->assertEquals(
            $this->addDataChain,
            $this->addDataChain->process(
                self::UNSUPPORTED_PROPERTY_NAME,
                $data,
                array('data' => 'test')
            ),
            'The AddDataChain::process must return itself if no next chain exist'
        );

        $this->assertFalse(
            array_key_exists(self::UNSUPPORTED_PROPERTY_NAME, $data),
            "Th AddDataChain mustn't inject data into array if the action requested is not supported"
        );

        $this->addDataChain->setNext($next);
        //suported action with array with next
        $data = array();
        $this->assertEquals(
            $next,
            $this->addDataChain->process(
                self::SUPPORTED_PROPERTY_NAME,
                $data,
                array('data' => 'test')
            ),
            'The AddDataChain::process must return the next chain if exist'
        );

        $this->assertTrue(
            array_key_exists(self::SUPPORTED_PROPERTY_NAME, $data),
            'Th AddDataChain must create data array key if the action requested is not supported'
        );

        $this->assertEquals(
            'test',
            $data[self::SUPPORTED_PROPERTY_NAME],
            'The AddDataChain must inject data into array if the requested action is supported'
        );
        //suported action with object method with next
        $data = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array('set'.ucfirst(self::SUPPORTED_PROPERTY_NAME)))
            ->getMock();
        $data->expects($this->once())
            ->method('set'.ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->with($this->equalTo('test'))
            ->will($this->returnSelf());

        $this->addDataChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array('data' => 'test')
        );
        //suported action with object property with next
        $data = new AddDataChainTestObject();

        $this->addDataChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            array('data' => 'test')
        );

        $this->assertEquals(
            'test',
            $data->testProperty,
            'AddDataChain::process must inject data into property if no one getter method exist'
        );
    }

    /**
     * Test action.
     *
     * This method return
     * the get action method
     * of the AddDataChain.
     */
    public function testAction()
    {
        $this->assertEquals(
            self::SUPPORTED_PROPERTY_NAME,
            $this->addDataChain->getAction(),
            "The AddDataChain must return it's own property as action"
        );
    }

    /**
     * Test support.
     *
     * This method test the
     * AddDataChain
     * support process.
     */
    public function testSupport()
    {
        $this->assertTrue(
            $this->addDataChain->support(self::SUPPORTED_PROPERTY_NAME),
            "The add data chain must support it's own property"
        );

        $this->assertFalse(
            $this->addDataChain->support(self::UNSUPPORTED_PROPERTY_NAME),
            "The add data chain mustn't support other property than it's own property"
        );
    }
}
