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

namespace Cscfa\Bundle\TwigUIBundle\Test\ChainOfResponsibility;

use Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Abstracts\AbstractChain;
use Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Interfaces\ChainInterface;

/**
 * AbstractChainTest.
 *
 * The AbstractChainTest is used to test the
 * AbstractChain.
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
     * First chain.
     *
     * This property store the first
     * chain element.
     *
     * @var AbstractChain|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $firstChain;

    /**
     * Second chain.
     *
     * This property store the second
     * chain element.
     *
     * @var AbstractChain|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $secondChain;

    /**
     * Set up.
     *
     * This method set up the test
     * class before tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->firstChain = $this->getMockForAbstractClass(AbstractChain::class);
        $this->secondChain = $this->getMockForAbstractClass(AbstractChain::class);
    }

    /**
     * Test next.
     *
     * This method test the AbstractChain next
     * chain support.
     */
    public function testNext()
    {
        $this->assertInstanceOf(
            ChainInterface::class,
            $this->firstChain->setNext($this->secondChain),
            'AbstractChain::setNext must return an instance of ChainInterface'
        );

        $this->assertSame(
            $this->secondChain,
            $this->firstChain->getNext(),
            'AbstractChain::getNext must return the stored next chain'
        );
    }
}
