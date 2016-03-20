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

use Cscfa\Bundle\TwigUIBundle\Builders\PropertyBuilderChain;
use Cscfa\Bundle\TwigUIBundle\Test\PropertyObject;
use Cscfa\Bundle\TwigUIBundle\Test\SetPropertyObject;
use Cscfa\Bundle\TwigUIBundle\Test\AddPropertyObject;

/**
 * PropertyBuilderChainTest.
 *
 * The PropertyBuilderChainTest is used to test the
 * PropertyBuilderChain.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class PropertyBuilderChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object.
     *
     * This method test the object support
     * of the PropertyBuilderChain.
     */
    public function testObject()
    {
        $chain = new PropertyBuilderChain();
        $chain->setProperty('property');

        $object = $this->getMock(SetPropertyObject::class);
        $object->expects($this->once())
            ->method('setProperty')
            ->will($this->returnSelf())
            ->with($this->equalTo('dataProperty'));
        $chain->build('property', 'dataProperty', $object);

        $object = $this->getMock(AddPropertyObject::class);
        $object->expects($this->once())
        ->method('addProperty')
        ->will($this->returnSelf())
        ->with($this->equalTo('dataProperty'));
        $chain->build('property', 'dataProperty', $object);

        $object = new PropertyObject();
        $chain->build('property', 'dataProperty', $object);
        $this->assertEquals(
            'dataProperty',
            $object->property,
            'The PropertyBuilderChain::build must inject data into property if no ' +
            'one setter exist and the property is public'
        );

        $chain2 = new PropertyBuilderChain();
        $chain2->setProperty('propertyMulti');
        $object = $this->getMock(SetPropertyObject::class);
        $object->expects($this->once())
            ->method('setPropertyMulti')
            ->will($this->returnSelf())
            ->with($this->equalTo('data0'), $this->equalTo('data1'), $this->equalTo('data2'));
        $chain2->build('propertyMulti', array('data0', 'data1', 'data2'), $object);
    }

    /**
     * Test array.
     *
     * This method test the array support
     * of the PropertyBuilderChain.
     */
    public function testArray()
    {
        $chain = new PropertyBuilderChain();
        $chain->setProperty('property');

        $array = array();
        $chain->build('property', 'dataProperty', $array);

        $this->assertArrayHasKey(
            'property',
            $array,
            'The PropertyBuilderChain::build must inject data into array if support the property'
        );
        $this->assertEquals(
            'dataProperty',
            $array['property'],
            'The PropertyBuilderChain::build must inject given data into array key'
        );
        $chain->build('prop', 'dataProperty', $array);
        $this->assertArrayNotHasKey(
            'prop',
            $array,
            "The PropertyBuilderChain::build mustn't inject data into array if" +
            ' not support the property'
        );
    }

    /**
     * Test to next.
     *
     * This method test the next chain
     * support of the PropertyBuilderChain.
     */
    public function testToNext()
    {
        $chain = new PropertyBuilderChain();
        $chain->setProperty('property');

        $nextChain = $this->getMock(PropertyBuilderChain::class);
        $nextChain->expects($this->once())
            ->method('build')
            ->will($this->returnSelf());

        $array = array();
        $this->assertSame(
            $chain,
            $chain->build('test', null, $array),
            'The PropertyBuilderChain::build must return itself if no one next chain is defined'
        );

        $chain->setNext($nextChain);
        $this->assertSame(
            $nextChain,
            $chain->build('test', null, $array),
            'The PropertyBuilderChain::build must return the final chained element if ' +
            'one next chain is defined'
        );
    }

    /**
     * Property provider.
     *
     * This method return a set of various
     * type to test the property support
     * of the PropertyBuilderChain instance.
     *
     * @return array
     */
    public function propertyProvider()
    {
        return array(
            array(openssl_random_pseudo_bytes(5), true),
            array(12, false),
            array(15.20, false),
            array(true, false),
            array(false, false),
            array(new \stdClass(), false),
        );
    }

    /**
     * Test property.
     *
     * This method test the property storage
     * support of the PropertyBuilderChain instance.
     *
     * @param mixed $property The property to use
     * @param bool  $noError  The error expected state
     *
     * @dataProvider propertyProvider
     */
    public function testProperty($property, $noError)
    {
        $chain = new PropertyBuilderChain();

        if ($noError) {
            $this->assertInstanceOf(
                PropertyBuilderChain::class,
                $chain->setProperty($property),
                'The PropertyBuilderChain::setProperty must return an instance of PropertyBuilderChain'
            );
            $this->assertEquals(
                $property,
                $chain->getProperty(),
                'The PropertyBuilderChain::getProperty must return the stored property'
            );
            $this->assertTrue(
                $chain->support($property),
                'The PropertyBuilderChain::support must return true if the property given is the same as stored'
            );
            $this->assertFalse(
                $chain->support(openssl_random_pseudo_bytes(10)),
                'The PropertyBuilderChain::support must return false if the property given is not the same as stored'
            );
        } else {
            try {
                $chain->setProperty($property);
                $this->fail('The PropertyBuilderChain::setProperty must throw exception if the given is not a string');
            } catch (\Exception $e) {
                $this->assertEquals(
                    500,
                    $e->getCode(),
                    'The PropertyBuilderChain::setProperty must throw exception with code 500 if' +
                    ' the given is not a string'
                );
            }
        }
    }
}
