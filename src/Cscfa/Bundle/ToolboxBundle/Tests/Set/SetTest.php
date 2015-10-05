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
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
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
 * @package  CscfaToolboxBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * 
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * test ListSet.
     * 
     * This method provide test
     * for the ListSet class.
     * 
     * @return void
     */
    public function testListSet()
    {
        $ls = new ListSet();
        
        $element = "work";
        $elements = array(
            "working",
            "worker",
            "is work"
        );
        
        $this->assertTrue($ls->isEmpty());
        
        $ls->add($element);
        $this->assertTrue($ls->contain($element));
        
        $ls->addAll($elements);
        $this->assertTrue($ls->containsAll($elements));
        $this->assertTrue($ls->get(0) === $element);
        
        $all = $ls->getAll();
        $this->assertTrue(in_array("work", $all));
        $this->assertTrue(in_array("working", $all));
        $this->assertTrue(in_array("worker", $all));
        $this->assertTrue(in_array("is work", $all));
        
        $this->assertFalse($ls->isEmpty());
        
        $ls->remove("work");
        $all = $ls->getAll();
        $this->assertFalse(in_array("work", $all));
        $this->assertTrue(in_array("working", $all));
        $this->assertTrue(in_array("worker", $all));
        $this->assertTrue(in_array("is work", $all));
        
        $ls->removeAll(array("working", "worker"));
        $all = $ls->getAll();
        $this->assertFalse(in_array("work", $all));
        $this->assertFalse(in_array("working", $all));
        $this->assertFalse(in_array("worker", $all));
        $this->assertTrue(in_array("is work", $all));
        
        $ls->clear();
        $this->assertTrue($ls->isEmpty());
    }

    /**
     * Test TypedList.
     * 
     * This method provide test
     * for TypedList class.
     * 
     * @return void
     */
    public function testTypedList()
    {
        $tl = new TypedList(new \DateTime());
        
        $tl->add("error");
        $this->assertFalse($tl->contain("error"));
        
        $element = new \DateTime("10:00:00");
        $elements = array(
            new \DateTime("11:00:00"),
            new \DateTime("12:00:00"),
            new \DateTime("13:00:00")
        );
        
        $this->assertTrue($tl->isEmpty());
        
        $tl->add($element);
        $this->assertTrue($tl->contain($element));
        
        $tl->addAll($elements);
        $this->assertTrue($tl->containsAll($elements));
        $this->assertTrue($tl->get(0) === $element);
        
        $all = $tl->getAll();
        $this->assertTrue(in_array($element, $all));
        $this->assertTrue(in_array($elements[0], $all));
        $this->assertTrue(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));
        
        $this->assertFalse($tl->isEmpty());
        
        $tl->remove($element);
        $all = $tl->getAll();
        $this->assertFalse(in_array($element, $all));
        $this->assertTrue(in_array($elements[0], $all));
        $this->assertTrue(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));
        
        $tl->removeAll(
            array(
                $elements[0],
                $elements[1]
            )
        );
        $all = $tl->getAll();
        $this->assertFalse(in_array($element, $all));
        $this->assertFalse(in_array($elements[0], $all));
        $this->assertFalse(in_array($elements[1], $all));
        $this->assertTrue(in_array($elements[2], $all));
        
        $tl->clear();
        $this->assertTrue($tl->isEmpty());
    }

    /**
     * Test HackSet.
     * 
     * This method provide test
     * for HackSet class.
     * 
     * @return void
     */
    public function testHackSet()
    {
        $hs = new HackSet();
        
        $this->assertTrue($hs->isEmpty());
        
        $hs->add("integer", 12);
        $hs->add("integer", 24);
        $this->assertTrue($hs->hasKey("integer"));
        $this->assertTrue($hs->hasValue(12) == "integer");
        $this->assertTrue($hs->hasValueIn("integer", 12));
        $this->assertTrue($hs->hasValue(24) == "integer");
        $this->assertTrue($hs->hasValueIn("integer", 24));
        $this->assertTrue($hs->hasRecord("integer"));
        
        $hs->add("hello", "world");
        $this->assertTrue(in_array("integer", $hs->getKeys()));
        $this->assertTrue(in_array("hello", $hs->getKeys()));
        
        $this->assertFalse($hs->isEmpty());
        
        $integer = $hs->get("integer");
        $this->assertTrue(in_array(12, $integer));
        $this->assertTrue(in_array(24, $integer));
        
        $all = $hs->getAll();
        $tall = array(
            "integer" => array(
                12,
                24
            ),
            "hello" => array(
                "world"
            )
        );
        $this->assertTrue($all == $tall);
        
        $hs->removeIn("integer", 12);
        $this->assertFalse($hs->hasValueIn("integer", 12));
        $this->assertFalse($hs->hasValue(12) == "integer");
        
        $hs->remove("integer");
        $this->assertFalse($hs->hasKey("integer"));
        
        $hs->clear();
        $this->assertTrue($hs->isEmpty());
    }
}
