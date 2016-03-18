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

namespace Cscfa\Bundle\TwigUIBundle\Test\Objects\TwigRequest;

use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * TwigRequestIteratorTest.
 *
 * The TwigRequestIteratorTest process the tests for
 * the TwigRequestIterator class.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigRequestIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Traversable.
     *
     * The tested TwigRequestIterator traversable
     * element.
     *
     * @var TwigRequestIterator
     */
    protected $traversable;

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
        $this->traversable = new TwigRequestIterator();
    }

    /**
     * Iteration provider.
     *
     * This method return a set of data
     * to test TwigRequestIterator
     * process.
     *
     * @return array
     */
    public function iterationProvider()
    {
        return array(
            array(
                array(
                    'a' => $this->getMock(TwigRequest::class),
                    'b' => $this->getMock(TwigRequest::class),
                    'd' => $this->getMock(TwigRequest::class),
                    'e' => $this->getMock(TwigRequest::class),
                    'f' => $this->getMock(TwigRequest::class),
                    'g' => $this->getMock(TwigRequest::class),
                    'h' => $this->getMock(TwigRequest::class),
                    'i' => $this->getMock(TwigRequest::class),
                    'j' => $this->getMock(TwigRequest::class),
                ),
                array(
                    'a' => true,
                    'b' => true,
                    'd' => true,
                    'e' => true,
                    'f' => true,
                    'g' => true,
                    'h' => true,
                    'i' => true,
                    'j' => true,
                ),
            ),
        );
    }

    /**
     * Test iteration.
     *
     * This method test the iteration process
     * of the TwigIterator instance.
     *
     * @param array $twigRequests A set of TwigRequest to inject
     * @param array $testArray    One array to rule them all... Used to check that each
     *                            injected instance are returned by the iteration
     *
     * @dataProvider iterationProvider
     */
    public function testIteration($twigRequests, $testArray)
    {
        foreach ($twigRequests as $alias => $element) {
            $this->assertSame(
                $this->traversable,
                $this->traversable->addTwigRequest($element, $alias),
                'TwigRequestIterator::addTwigRequest must return itself'
            );
        }

        $this->traversable->rewind();

        $loop = true;
        while ($loop) {
            $loop = $this->traversable->valid();

            if ($loop) {
                $key = $this->traversable->key();
                $element = $this->traversable->current();

                $this->assertTrue(
                    array_key_exists($key, $twigRequests),
                    'TwigRequestIterator::key must return a valid alias'
                );
                $this->assertTrue(
                    array_search($element, $twigRequests, true) === false ? false : true,
                    'TwigRequestIterator::current must return a valid TwigRequest instance'
                );

                $testArray[$key] = false;
            }

            $this->traversable->next();
        }

        $this->assertFalse(
            array_search(true, $testArray),
            'TwigRequestIterator must be able to return all of the added TwigRequest'
        );
    }
}
