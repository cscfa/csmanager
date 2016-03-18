<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Entity\Builders;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\DefaultEntityBuilder;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * DefaultEntityBuilderTest.
 *
 * The DefaultEntityBuilderTest
 * process the DefaultEntityBuilder
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\DefaultEntityBuilder
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\DefaultEntityBuilder
 */
class DefaultEntityBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default builder.
     *
     * This property register the DefaultEntityBuilder
     * instance to be tested.
     *
     * @var DefaultEntityBuilder
     */
    protected $defaultBuilder;

    /**
     * Entity.
     *
     * The mocked entity to build
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entity;

    /**
     * Chain.
     *
     * This property register the chain
     * of responsablity that register the
     * DefaultEntityBuilder instance.
     *
     * @var ChainOfResponsibilityInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $chain;

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
        $this->defaultBuilder = new DefaultEntityBuilder();

        $this->entity = $this->getMockBuilder(\stdClass::class)
            ->setMethods(array('setProperty', 'getProperty'))
            ->getMock();

        $this->defaultBuilder->setEntity($this->entity);

        $this->chain = $this->getMockBuilder(ChainOfResponsibilityInterface::class)
            ->setMethods(array('process', 'setNext', 'getNext', 'support', 'getAction'))
            ->getMock();

        $this->defaultBuilder->setProcessChain($this->chain);
    }

    /**
     * Test entity.
     *
     * This method process test to check
     * if the given entity is currently
     * registered and retreivable.
     */
    public function testEntity()
    {
        $this->assertSame(
            $this->entity,
            $this->defaultBuilder->getEntity(),
            'The DefaultEntityBuilder must return the setted entity when call getEntity'
        );
    }

    /**
     * Test chain.
     *
     * This method process test to check
     * if the given chain is currently
     * registered and retreivable.
     */
    public function testChain()
    {
        $this->assertSame(
            $this->chain,
            $this->defaultBuilder->getProcessChain(),
            'The DefaultEntityBuilder must return the setted entity when call getEntity'
        );
    }

    /**
     * Test add without extra.
     *
     * This method process test to check
     * that the builder use the chain
     * to apply add property to the entity.
     */
    public function testAddWithoutExtra()
    {
        $property = 'property';
        $data = 'injectedData';

        $this->chain->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($property),
                $this->identicalTo($this->entity),
                $this->equalTo(array('data' => $data))
            )->will($this->returnSelf());

        $this->defaultBuilder->add($property, $data);
    }

    /**
     * Test add without extra.
     *
     * This method process test to check
     * that the builder use the chain
     * to apply add property to the entity
     * and passing extra data.
     */
    public function testAddWithExtra()
    {
        $property = 'property';
        $data = 'injectedData';

        $this->chain->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($property),
                $this->identicalTo($this->entity),
                $this->equalTo(array('data' => $data, 'extra' => 'extraData'))
            )->will($this->returnSelf());

        $this->defaultBuilder->add($property, $data, array('extra' => 'extraData'));
    }

    /**
     * Test add without extra.
     *
     * This method process test to check
     * that the builder doesn't use the chain
     * to apply add property to unexistant
     * entity.
     */
    public function testAddWithoutEntity()
    {
        $property = 'property';
        $data = 'injectedData';

        $this->chain->expects($this->never())
            ->method('process');
        $this->defaultBuilder->setEntity(null);
        $this->defaultBuilder->add($property, $data);
    }
}
