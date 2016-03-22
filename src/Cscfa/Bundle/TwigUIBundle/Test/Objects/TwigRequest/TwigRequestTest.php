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

use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * TwigRequestTest.
 *
 * The TwigRequestTest process the tests for
 * the TwigRequest class.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Twig request.
     *
     * This property register the TwigRequest
     * instance to use on tests.
     *
     * @var TwigRequest
     */
    protected $twigRequest;

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
        $this->twigRequest = new TwigRequest();
    }

    /**
     * Twig path provider.
     *
     * This method return data tests for
     * setTwigPath tests.
     *
     * @return array
     */
    public function twigPathProvider()
    {
        $result = array();
        for ($index = 0; $index < 5; ++$index) {
            $result[] = array(openssl_random_pseudo_bytes(mt_rand(4, 30)), true);
        }
        $result[] = array(true, false);
        $result[] = array(false, false);
        $result[] = array(null, true);
        $result[] = array(14, false);

        return $result;
    }

    /**
     * Test twig path.
     *
     * This method test the twig path setter and
     * getter TwigRequest process.
     *
     * @param string $path        The path to insert
     * @param bool   $noException The process mustn't return exception
     *
     * @dataProvider twigPathProvider
     */
    public function testTwigPath($path, $noException)
    {
        if ($noException) {
            $this->assertSame(
                $this->twigRequest,
                $this->twigRequest->setTwigPath($path),
                'The TwigRender::setTwigPath must return itself'
            );
            $this->assertEquals(
                $path,
                $this->twigRequest->getTwigPath(),
                'TwigRequest::getTwigPath must return the registered path'
            );
        } else {
            try {
                $this->twigRequest->setTwigPath($path);
                $this->fail('TwigRequest::setTwigPath must return' +
                    'exception if the given path is not string or null');
            } catch (\Exception $e) {
                $this->assertEquals(
                    500,
                    $e->getCode(),
                    'TwigRequest::setTwigPath must return exception with ' +
                    'code 500 if the given path is not string or null'
                );
            }
        }
    }

    /**
     * Arguments provider.
     *
     * This method provide test data for
     * the argument test process.
     *
     * @return array
     */
    public function argumentsProvider()
    {
        $result = array();

        for ($index = 0; $index < 10; ++$index) {
            $result[] = array(
                array(
                    openssl_random_pseudo_bytes(mt_rand(4, 15)),
                    openssl_random_pseudo_bytes(mt_rand(4, 15)),
                ),
                array(
                    mt_rand(1, 13),
                    mt_rand(1, 13),
                ),
            );
        }

        return $result;
    }

    /**
     * Test arguments.
     *
     * This method process test for argument
     * process of TwigRequest.
     *
     * @param array $argNames A set of arguments name
     * @param array $values   A set of arguments values
     *
     * @dataProvider argumentsProvider
     */
    public function testArguments($argNames, $values)
    {
        $mockedTwigRequest = $this->getMockBuilder(TwigRequest::class)
            ->setMethods(array('addArgument'))
            ->getMock();
        $mockedTwigRequest->expects($this->exactly(2))
            ->method('addArgument')
            ->will($this->returnSelf());
        for ($index = 0; $index < 2; ++$index) {
            $mockedTwigRequest->expects($this->at($index))
                ->method('addArgument')
                ->with($argNames[$index], $values[$index]);
        }
        $mockedTwigRequest->setArguments(array(
            $argNames[0] => $values[0],
            $argNames[1] => $values[1],
        ));

        $this->twigRequest->setArguments(array(
            $argNames[0] => $values[0],
            $argNames[1] => $values[1],
        ));

        for ($index = 0; $index < 2; ++$index) {
            $this->assertTrue(
                $this->twigRequest->hasArgument($argNames[$index]),
                'The TwigRequest::hasArgument must return true if argument exist'
            );
        }

        $this->assertSame(
            $this->twigRequest,
            $this->twigRequest->remArgument($argNames[1]),
            'The TwigRequest::remArgument must return itself'
        );
        $this->assertEquals(
            array($argNames[0] => $values[0]),
            $this->twigRequest->getArguments(),
            'The TwigRequest::getTwigArguments method must return stored arguments'
        );

        $exceptionTest = array(
            'boolean' => true,
            'boolean' => false,
            'array' => array(),
            'integer' => 12,
            'double' => 14.5,
        );
        foreach ($exceptionTest as $exceptionValue) {
            try {
                $this->twigRequest->addArgument($exceptionValue, null);
                $this->fail(
                    'The TwigRequest::addArgument must throw exception if' +
                    'argument name is not string'
                );
            } catch (\Exception $e) {
                $this->assertEquals(
                    500,
                    $e->getCode(),
                    'The TwigRequest::addArgument must throw exception with' +
                    'code 500 if argument name is not string'
                );
            }
        }
    }

    /**
     * Test childs.
     *
     * This method test the TwigRequest
     * child support.
     */
    public function testChilds()
    {
        $childAlias = array('child1', 'child2');
        $childElement = array(new TwigRequest(), new TwigRequest());

        for ($index = 0; $index < 2; ++$index) {
            $this->twigRequest->addChildRequest(
                $childElement[$index],
                $childAlias[$index]
            );
        }

        $index = 0;
        foreach ($this->twigRequest->getChilds() as $alias => $child) {
            $this->assertEquals(
                $childAlias[$index],
                $alias,
                'TwigRequest::getChild must return the registered elements'
            );
            $this->assertEquals(
                $childElement[$index],
                $child,
                'TwigRequest::getChild must return the registered elements'
            );
            ++$index;
        }
    }
}
