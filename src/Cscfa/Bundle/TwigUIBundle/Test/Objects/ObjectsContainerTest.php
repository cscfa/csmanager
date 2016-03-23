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

namespace Cscfa\Bundle\TwigUIBundle\Test\Objects;

use Cscfa\Bundle\TwigUIBundle\Object\ObjectsContainer;

/**
 * ObjectContainerTest.
 *
 * The ObjectContainerTest is used to test the
 * ObjectContainer.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ObjectsContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object container.
     *
     * This property store the tested
     * ObjectContainer instance.
     *
     * @var ObjectsContainer
     */
    protected $objectContainer;

    /**
     * Set up.
     *
     * This method allow to set up the
     * test class before tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->objectContainer = new ObjectsContainer();
    }

    /**
     * Mixed provider.
     *
     * This method provide a set of mixed data to test
     * objects comportment.
     *
     * @return array
     */
    public function mixedProvider()
    {
        return array(
            array(true, new \stdClass(), 'alias'),
            array(false, 'string', 'alias'),
            array(false, true, 'alias'),
            array(false, 12, 'alias'),
            array(false, 22.56, 'alias'),
            array(false, array(), 'alias'),
            array(true, null, 'alias'),
        );
    }

    /**
     * Test addObject.
     *
     * This test check if the ObjectContainer process object and fail if
     * the given data is not an object.
     *
     * @param bool  $isObject The given data is an object type or not
     * @param mixed $mixed    The given data
     *
     * @dataProvider mixedProvider
     */
    public function testAddObject($isObject, $mixed, $alias)
    {
        if ($isObject) {
            $this->assertSame(
                $this->objectContainer,
                $this->objectContainer->addObject($mixed, $alias),
                'ObjectContainer::addObject must return itself if the passed data is object'
            );
        } else {
            try {
                $this->objectContainer->addObject($mixed, $alias);
                $this->fail('ObjectContainer::addObject must throw exception if the passed data is not object');
            } catch (\Exception $exception) {
                $this->assertEquals(
                    500,
                    $exception->getCode(),
                    sprintf(
                        'ObjectContainer::addObject must throw exception with code 500' +
                        ' if the passed data is not object. %s throwed',
                        $exception->getCode()
                    )
                );
            }
        }
    }

    /**
     * Aliased provider.
     *
     * This method return a set of aliased object
     * and undefined alias to test the ObjectContainer
     * registration and checking process.
     *
     * @return array
     */
    public function aliasedProvider()
    {
        $result = array();
        for ($index = 0; $index < 10; ++$index) {
            $aliased = openssl_random_pseudo_bytes(5);
            $notAliased = openssl_random_pseudo_bytes(4);
            array_push(
                $result,
                array(
                    $aliased,
                    new \stdClass(),
                    $notAliased,
                )
            );
        }

        return $result;
    }

    /**
     * Test has object.
     *
     * This method test the registration and check
     * existance process of the ObjectsContainer instance.
     *
     * @param string $trueAlias  The true alias name
     * @param object $object     The object to register
     * @param string $falseAlias The wrong alias name
     *
     * @dataProvider aliasedProvider
     */
    public function testHasObject($trueAlias, $object, $falseAlias)
    {
        $this->objectContainer->addObject($object, $trueAlias);

        $this->assertTrue(
            $this->objectContainer->hasObject($trueAlias),
            'The ObjectContainer::hasObject must return true when it check an existant object'
        );
        $this->assertFalse(
            $this->objectContainer->hasObject($falseAlias),
            'The ObjectContainer::hasObject must return false when it check an unexistant object'
        );
    }

    /**
     * Test get object.
     *
     * This method test the get object process
     * of the ObjectsContainer instance.
     *
     * @param string $trueAlias  The true alias name
     * @param object $object     The object to register
     * @param string $falseAlias The wrong alias name
     *
     * @dataProvider aliasedProvider
     */
    public function testGetObject($trueAlias, $object, $falseAlias)
    {
        $this->objectContainer->addObject($object, $trueAlias);

        $this->assertNull(
            $this->objectContainer->getObject($falseAlias),
            'The ObjectContainer::getObject must return null if the requested alias is undefined'
        );
        $this->assertSame(
            $object,
            $this->objectContainer->getObject($trueAlias),
            'The ObjectContainer::getObject must return the stored object' +
            ' if the requested alias is defined'
        );
    }
}
