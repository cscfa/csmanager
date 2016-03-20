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

use Cscfa\Bundle\TwigUIBundle\Builders\Abstracts\AbstractBuilder;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderInterface;

/**
 * AbstractBuilderTest.
 *
 * The AbstractBuilderTest is used to test the
 * AbstractBuilder.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AbstractBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Builder.
     *
     * The abstract builder instance to
     * test.
     *
     * @var AbstractBuilder|\PHPUnit_Framework_MockObject_MockObject
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
        $this->builder = $this->getMockBuilder(AbstractBuilder::class)
            ->setMethods(array('add'))
            ->getMock();
    }

    /**
     * Element provider.
     *
     * This method provide a set of
     * elkements to be stored by the
     * AbstractBuilder.
     *
     * @return array
     */
    public function elementProvider()
    {
        return array(
            array(new \stdClass()),
            array('&é'),
            array(12),
            array(14.40),
            array(true),
        );
    }

    /**
     * Test element.
     *
     * This method test the element storage
     * process of the AbstractBuilder.
     *
     * @param mixed $element The element to Store
     *
     * @dataProvider elementProvider
     */
    public function testElement($element)
    {
        $this->assertInstanceOf(
            BuilderInterface::class,
            $this->builder->setElement($element),
            'The AbstractBuilder::setElement must return an instance of BuilderInterface'
        );

        $this->assertSame(
            $element,
            $this->builder->getResult(),
            'The AbstractBuilder::getResult must return the element stored by' +
            'AbstractBuilder::setElement previously'
        );
    }
}
