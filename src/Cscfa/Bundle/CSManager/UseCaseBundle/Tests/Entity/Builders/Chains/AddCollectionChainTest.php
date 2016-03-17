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

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddCollectionChain;
use Cscfa\Bundle\CSManager\UseCaseBundle\Tests\MockObject\AddCollectionChainTestObject;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * AddCollectionChainTest.
 *
 * The AddCollectionChainTest
 * process the AddCollectionChain
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains\AddCollectionChain
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddCollectionChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * addCollectionChain.
     *
     * This method register the
     * tested AddCollectionChain
     *
     * @var AddCollectionChain
     */
    protected $addCollectionChain;

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
        $this->addCollectionChain = new AddCollectionChain();
        $this->addCollectionChain->setProperty(self::SUPPORTED_PROPERTY_NAME);
    }

    /**
     * Test process.
     *
     * This method test the
     * process method of the
     * AddCollectionChain.
     */
    public function testProcess()
    {
        $next = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array('setNext', 'getNext', 'process', 'support', 'getAction'))
            ->getMock();

        $next->expects($this->exactly(4))
            ->method('process')
            ->will($this->returnSelf());

        // Unsuported array insertion
        $data = array(self::UNSUPPORTED_PROPERTY_NAME => '');
        $this->assertEquals(
            $this->addCollectionChain,
            $this->addCollectionChain->process(
                self::UNSUPPORTED_PROPERTY_NAME,
                $data,
                ['data' => 'test']
            ),
            'The AddCollectionChain process must return itself if no next chain exist'
        );

        $this->assertEquals(
            '',
            $data[self::UNSUPPORTED_PROPERTY_NAME],
            "The AddCollectionChain mustn't inject data into array if it not support action type"
        );

        $this->addCollectionChain->setNext($next);

        // Supported array insertion
        $data = array(self::SUPPORTED_PROPERTY_NAME => '');
        $this->assertEquals(
            $next,
            $this->addCollectionChain->process(
                self::SUPPORTED_PROPERTY_NAME,
                $data,
                ['data' => 'test']
            ),
            'The AddCollectionChain process must return it next if a next chain exist'
        );

        $this->assertEquals(
            array('test'),
            $data[self::SUPPORTED_PROPERTY_NAME],
            'The AddCollectionChain must inject data into array'
        );

        $data = array();
        $this->addCollectionChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $data,
            ['data' => 'test']
        );

        $this->assertEquals(
            array('test'),
            $data[self::SUPPORTED_PROPERTY_NAME],
            'The AddCollectionChain must create key and inject data into array'
        );

        // Multi insert into object by methods
        $dataStub = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array('add'))
            ->getMock();
        $dataStub->expects($this->exactly(2))
            ->method('add');
        $dataStub->expects($this->at(0))
            ->method('add')
            ->will($this->returnSelf())
            ->with($this->equalTo('test1'));
        $dataStub->expects($this->at(1))
            ->method('add')
            ->will($this->returnSelf())
            ->with($this->equalTo('test2'));

        $containerMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array('get'.ucfirst(self::SUPPORTED_PROPERTY_NAME)))
            ->getMock();
        $containerMock->expects($this->exactly(2 * 2))
            ->method('get'.ucfirst(self::SUPPORTED_PROPERTY_NAME))
            ->will($this->returnValue($dataStub));

        $this->addCollectionChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $containerMock,
            ['data' => array('test1', 'test2')]
        );

        // Multi insert into object by property
        $dataStub = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array('add'))
            ->getMock();
        $dataStub->expects($this->exactly(2))
            ->method('add');
        $dataStub->expects($this->at(0))
            ->method('add')
            ->will($this->returnSelf())
            ->with($this->equalTo('test1'));
        $dataStub->expects($this->at(1))
            ->method('add')
            ->will($this->returnSelf())
            ->with($this->equalTo('test2'));

        $container = new AddCollectionChainTestObject();
        $container->testProperty = $dataStub;

        $this->addCollectionChain->process(
            self::SUPPORTED_PROPERTY_NAME,
            $container,
            ['data' => array('test1', 'test2')]
        );
    }

    /**
     * Test action.
     *
     * This method return
     * the get action method
     * of the AddCollectionChain.
     */
    public function testAction()
    {
        $this->assertEquals(
            self::SUPPORTED_PROPERTY_NAME,
            $this->addCollectionChain->getAction(),
            "The AddCollectionChain must return it's own property as action"
        );
    }

    /**
     * Test support.
     *
     * This method test the
     * AddCollectionChain
     * support process.
     */
    public function testSupport()
    {
        $this->assertTrue(
            $this->addCollectionChain->support(self::SUPPORTED_PROPERTY_NAME),
            "The add collection chain must support it's own property"
        );

        $this->assertFalse(
            $this->addCollectionChain->support(self::UNSUPPORTED_PROPERTY_NAME),
            "The add collection chain mustn't support other property than it's own property"
        );
    }
}
