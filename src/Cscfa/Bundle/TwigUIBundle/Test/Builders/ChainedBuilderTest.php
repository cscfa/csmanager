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

namespace Cscfa\Bundle\TwigUIBundle\Test\Builders;

use Cscfa\Bundle\TwigUIBundle\Builders\ChainedBuilder;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderChainInterface;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\ChainedBuilderInterface;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderInterface;

/**
 * ChainedBuilderTest.
 *
 * The ChainedBuilderTest is used to test the
 * ChainedBuilder.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ChainedBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Builder.
     *
     * This property store the ChainedBuilder
     * for the tests.
     *
     * @var ChainedBuilder
     */
    protected $builder;

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
        $this->builder = new ChainedBuilder();
    }

    /**
     * Test chain adding.
     *
     * This method allow to test the chain
     * support of the ChainedBuilder.
     */
    public function testChainAdding()
    {
        $first = $this->getMockBuilder(BuilderChainInterface::class)
            ->setMethods(array('build', 'support', 'setNext', 'getNext'))
            ->getMock();

        $second = $this->getMockBuilder(BuilderChainInterface::class)
            ->setMethods(array('build', 'support', 'setNext', 'getNext'))
            ->getMock();

        $first->expects($this->once())
            ->method('setNext')
            ->with($this->identicalTo($second));
        $first->expects($this->once())
            ->method('build')
            ->with(
                $this->equalTo('property'),
                $this->equalTo('data')
            );

        $this->assertInstanceOf(
            ChainedBuilderInterface::class,
            $this->builder->addChain($first),
            'The BuilderChainInterface::addChain must return a ChainedBuilderInterface instance'
        );

        $this->builder->addChain($second);

        $this->assertInstanceOf(
            BuilderInterface::class,
            $this->builder->add('property', 'data'),
            'The BuilderChainInterface::addChain must return a ChainedBuilderInterface instance'
        );
    }
}
