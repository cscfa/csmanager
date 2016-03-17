<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
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

namespace Cscfa\Bundle\ToolboxBundle\Tests\Set;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\ToolboxBundle\Set\ListSet;
use Cscfa\Bundle\ToolboxBundle\Set\TypedList;
use Cscfa\Bundle\ToolboxBundle\Set\HackSet;

/**
 * SetTest class.
 *
 * The SetTest class provide
 * test to valid Sets classes.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class SetTest extends WebTestCase
{
    /**
     * The setUp.
     *
     * This method is used to configure
     * and process the initialisation of
     * the test class.
     */
    public function setUp()
    {
    }

    /**
     * test ListSet.
     *
     * This method provide test
     * for the ListSet class.
     */
    public function testListSet()
    {
        $listSet = new ListSet();

        $element = 'work';
        $elements = array(
            'working',
            'worker',
            'is work',
        );

        $this->assertTrue($listSet->isEmpty());

        $listSet->add($element);
        $this->assertTrue($listSet->contain($element));

        $listSet->addAll($elements);
        $this->assertTrue($listSet->containsAll($elements));
        $this->assertTrue($listSet->get(0) === $element);

        $all = $listSet->getAll();
        $this->assertTrue(in_array('work', $all));
        $this->assertTrue(in_array('working', $all));
        $this->assertTrue(in_array('worker', $all));
        $this->assertTrue(in_array('is work', $all));

        $this->assertFalse($listSet->isEmpty());

        $listSet->remove('work');
        $all = $listSet->getAll();
        $this->assertFalse(in_array('work', $all));
        $this->assertTrue(in_array('working', $all));
        $this->assertTrue(in_array('worker', $all));
        $this->assertTrue(in_array('is work', $all));

        $listSet->removeAll(array('working', 'worker'));
        $all = $listSet->getAll();
        $this->assertFalse(in_array('work', $all));
        $this->assertFalse(in_array('working', $all));
        $this->assertFalse(in_array('worker', $all));
        $this->assertTrue(in_array('is work', $all));

        $listSet->clear();
        $this->assertTrue($listSet->isEmpty());
    }

    /**
     * Test TypedList.
     *
     * This method provide test
     * for TypedList class.
     */
    public function testTypedList()
    {
        $typedList = new TypedList(new \DateTime());

        $typedList->add('error');
        $this->assertFalse($typedList->contain('error'));

        $element = new \DateTime('10:00:00');
        $elements = array(
            new \DateTime('11:00:00'),
            new \DateTime('12:00:00'),
            new \DateTime('13:00:00'),
        );

        $this->assertTrue($typedList->isEmpty());

        $typedList->add($element);
        $this->assertTrue($typedList->contain($element));

        $typedList->addAll($elements);
        $this->assertTrue($typedList->containsAll($elements));
        $this->assertTrue($typedList->get(0) === $element);

        $all = $typedList->getAll();
        $this->assertTrue(in_array($element, $all));
        $this->assertTrue(in_array($elements[0], $all));
        $this->assertTrue(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));

        $this->assertFalse($typedList->isEmpty());

        $typedList->remove($element);
        $all = $typedList->getAll();
        $this->assertFalse(in_array($element, $all));
        $this->assertTrue(in_array($elements[0], $all));
        $this->assertTrue(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));

        $typedList->removeAll(
            array(
                $elements[0],
                $elements[1],
            )
        );
        $all = $typedList->getAll();
        $this->assertFalse(in_array($element, $all));
        $this->assertFalse(in_array($elements[0], $all));
        $this->assertFalse(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));

        $typedList->clear();
        $this->assertTrue($typedList->isEmpty());
    }

    /**
     * Test HackSet.
     *
     * This method provide test
     * for HackSet class.
     */
    public function testHackSet()
    {
        $hackSet = new HackSet();

        $this->assertTrue($hackSet->isEmpty());

        $hackSet->add('integer', 12);
        $hackSet->add('integer', 24);
        $this->assertTrue($hackSet->hasKey('integer'));
        $this->assertTrue($hackSet->hasValue(12) == 'integer');
        $this->assertTrue($hackSet->hasValueIn('integer', 12));
        $this->assertTrue($hackSet->hasValue(24) == 'integer');
        $this->assertTrue($hackSet->hasValueIn('integer', 24));
        $this->assertTrue($hackSet->hasRecord('integer'));

        $hackSet->add('hello', 'world');
        $this->assertTrue(in_array('integer', $hackSet->getKeys()));
        $this->assertTrue(in_array('hello', $hackSet->getKeys()));

        $this->assertFalse($hackSet->isEmpty());

        $integer = $hackSet->get('integer');
        $this->assertTrue(in_array(12, $integer));
        $this->assertTrue(in_array(24, $integer));

        $all = $hackSet->getAll();
        $tall = array(
            'integer' => array(
                12,
                24,
            ),
            'hello' => array(
                'world',
            ),
        );
        $this->assertTrue($all == $tall);

        $hackSet->removeIn('integer', 12);
        $this->assertFalse($hackSet->hasValueIn('integer', 12));
        $this->assertFalse($hackSet->hasValue(12) == 'integer');

        $hackSet->remove('integer');
        $this->assertFalse($hackSet->hasKey('integer'));

        $hackSet->clear();
        $this->assertTrue($hackSet->isEmpty());
    }
}
