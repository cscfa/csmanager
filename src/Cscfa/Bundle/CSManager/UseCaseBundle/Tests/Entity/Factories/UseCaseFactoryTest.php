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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Entity\Factories;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\UseCaseFactory;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\DefaultEntityBuilder;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCase;

/**
 * DefaultEntityBuilderTest.
 *
 * The DefaultEntityBuilderTest
 * process the DefaultEntityBuilder
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\UseCaseFactory
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\UseCaseFactory
 */
class UseCaseFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Factory.
     *
     * This property register the
     * UseCaseFactory instance to
     * test
     *
     * @var UseCaseFactory
     */
    protected $factory;

    /**
     * Builder.
     *
     * This property register the mocked
     * DefaultEntityBuilder that the
     * factory use.
     *
     * @var DefaultEntityBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $builder;

    /**
     * Set up.
     *
     * This method set up the test class
     * before tests.
     */
    public function setUp()
    {
        $this->factory = new UseCaseFactory();

        $this->builder = $this->getMockBuilder(DefaultEntityBuilder::class)
            ->setMethods(array('add'))
            ->getMock();
    }

    /**
     * Test create without data.
     *
     * This method process test to check if
     * the result of UseCaseFactory::getInstance
     * match UseCase instance of.
     */
    public function testCreateWhithoutData()
    {
        $this->factory->setBuilder($this->builder);

        $this->assertInstanceOf(
            UseCase::class,
            $this->factory->getInstance(),
            'UseCaseFactory must return an instance of UseCase'
        );
    }

    /**
     * Test create with data.
     *
     * This method process test to check if
     * the result of UseCaseFactory::getInstance
     * is a builded UseCase instance.
     */
    public function testCreateWhithData()
    {
        $options = array(
            array('property' => 'name', 'data' => 'injectName'),
            array('property' => 'description', 'data' => 'injectDescription'),
            array('property' => 'extra', 'data' => 'injectExtra', 'options' => array('area' => '3')),
            array('unavailable' => true),
        );

        $this->builder->expects($this->exactly(3))
            ->method('add')
            ->will($this->returnSelf());
        $this->builder->expects($this->at(0))
            ->method('add')
            ->with(
                $this->equalTo('name'),
                $this->equalTo('injectName'),
                $this->equalTo(array())
            );
        $this->builder->expects($this->at(1))
            ->method('add')
            ->with(
                $this->equalTo('description'),
                $this->equalTo('injectDescription'),
                $this->equalTo(array())
            );
        $this->builder->expects($this->at(2))
            ->method('add')
            ->with(
                $this->equalTo('extra'),
                $this->equalTo('injectExtra'),
                $this->equalTo(array('area' => '3'))
            );

        $this->factory->setBuilder($this->builder);

        $this->assertInstanceOf(
            UseCase::class,
            $this->factory->getInstance($options),
            'UseCaseFactory must return an instance of UseCase'
        );
    }
}
