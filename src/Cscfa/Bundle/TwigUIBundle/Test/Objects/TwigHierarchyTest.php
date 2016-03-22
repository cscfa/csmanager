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

use Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy\TwigHierarchy;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * TwigHierarchyTest.
 *
 * The TwigHierarchyTest is used to test the
 * TwigHierarchy.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigHierarchyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Twig hierarchy.
     *
     * This property register the
     * tested TwigHierarchy instance.
     *
     * @var TwigHierarchy
     */
    protected $twigHierarchy;

    /**
     * Twig iterator.
     *
     * This property register the main
     * twig hierarchy registry.
     *
     * @var TwigRequestIterator
     */
    protected $twigIterator;

    /**
     * Set up.
     *
     * This method set up the test class before
     * tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->twigHierarchy = new TwigHierarchy();

        $this->twigIterator = new TwigRequestIterator();

        $this->twigHierarchy->setMainRegistry(
            $this->twigIterator
        );
    }

    /**
     * Test registry.
     *
     * This method test the registry
     * storage process of the TwigHierarchy
     * instance.
     */
    public function testRegistry()
    {
        $this->assertSame(
            $this->twigHierarchy,
            $this->twigHierarchy->setMainRegistry($this->twigIterator),
            'TwigHierarchy::setMainRegistry must return itself'
        );

        $this->assertSame(
            $this->twigIterator,
            $this->twigHierarchy->getMainRegistry(),
            'TwigHierarchy::getMainRegistry must return the registered registry'
        );
    }

    /**
     * Test verticality.
     *
     * This method test the hierarchical
     * TwigRequest registration.
     */
    public function testVerticality()
    {
        $this->assertSame(
            $this->twigHierarchy,
            $this->twigHierarchy->startHierarchy(),
            'TwigHierarchy::startHierarchy must return itself'
        );

        $this->assertSame(
            $this->twigHierarchy,
            $this->twigHierarchy->register(new TwigRequest(), 'topLevel'),
            'TwigHierarchy::startHierarchy must return itself'
        );

        $this->assertSame(
            $this->twigHierarchy,
            $this->twigHierarchy->downStep(),
            'TwigHierarchy::startHierarchy must return itself'
        );

        $this->twigHierarchy->register(new TwigRequest(), 'level1');
        $this->twigHierarchy->startHierarchy()
            ->downStep()
            ->downStep()
            ->startHierarchy();
        $this->twigHierarchy->register(new TwigRequest(), 'topLevel2');

        $first = true;
        $topUnused = true;
        foreach ($this->twigIterator as $key => $value) {
            $topUnused = false;
            if ($first) {
                $first = false;

                $this->assertEquals(
                    'topLevel',
                    $key,
                    'The hierarchy top level must register the top level request'
                );

                if ($value instanceof TwigRequest) {
                    $unused = true;
                    foreach ($value->getChilds() as $childKey => $child) {
                        $unused = false;

                        $this->assertEquals(
                            'level1',
                            $childKey,
                            'The hierarchy first level must register the first level request'
                        );
                        $this->assertInstanceOf(
                            TwigRequest::class,
                            $child,
                            'The hierarchy first level must register the first level request'
                        );
                    }

                    if ($unused) {
                        $this->fail(
                            'The hierarchy first level must register the first level request'
                        );
                    }
                }
            } else {
                $this->assertEquals(
                    'topLevel2',
                    $key,
                    'The hierarchy second top level must register the second top level request'
                );
            }
        }
        if ($topUnused) {
            $this->fail(
                'The hierarchy must register the requests'
            );
        }
    }
}
