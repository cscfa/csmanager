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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Form\Factories;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\UseCaseFormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\FormStrategyInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\UseCaseFactory;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCase;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\UseCaseType;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\UseCaseStrategyInterface;

/**
 * AbstractStrategistFormTest.
 *
 * The AbstractStrategistFormTest
 * process the AbstractStrategistForm
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\UseCaseFormFactory
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\UseCaseFormFactory
 */
class UseCaseFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Strategist.
     *
     * This property register the AbstractStrategistForm
     * instance to be tested.
     *
     * @var UseCaseFormFactory
     */
    protected $strategist;

    /**
     * formFactory.
     *
     * This property register the FormFactoryInterface
     * mocked instance to be tested.
     *
     * @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $formFactory;

    /**
     * Strategy.
     *
     * This property register the FormStrategyInterface
     * mocked instance to be tested.
     *
     * @var UseCaseStrategyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $strategy;

    /**
     * Use case factory.
     *
     * This property register the UseCaseFactory
     * mocked instance to be tested.
     *
     * @var UseCaseFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $useCaseFactory;

    /**
     * Use case.
     *
     * This property register the UseCase
     * mocked instance to be tested.
     *
     * @var UseCase|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $useCase;

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
        $this->strategist = new UseCaseFormFactory();

        $this->formFactory = $this->getMockForAbstractClass(FormFactoryInterface::class);

        $this->strategy = $this->getMockForAbstractClass(UseCaseStrategyInterface::class);

        $this->useCaseFactory = $this->getMock(UseCaseFactory::class);

        $this->useCase = $this->getMock(UseCase::class);
    }

    /**
     * Test form.
     *
     * This method process test
     * for UseCaseFactory form
     * creation.
     */
    public function testForm()
    {
        $this->useCaseFactory->expects($this->exactly(2))
            ->method('getInstance')
            ->will($this->returnValue($this->useCase));
        $this->useCaseFactory->expects($this->at(0))
            ->method('getInstance')
            ->with($this->equalTo(array()));
        $this->useCaseFactory->expects($this->at(1))
            ->method('getInstance')
            ->with($this->equalTo(array('extra' => 'extraData')));

        $this->formFactory->expects($this->exactly(2))
            ->method('create')
            ->with(
                $this->equalTo(((new UseCaseType())->setStrategy($this->strategy))),
                $this->identicalTo($this->useCase)
            )
            ->will($this->returnValue(null));

        $this->strategist->setFormFactory($this->formFactory);
        $this->strategist->setUseCaseFactory($this->useCaseFactory);
        $this->strategist->addStrategy($this->strategy, 'testedStrategy');
        $this->strategist->useStrategy('testedStrategy');

        $this->assertNull(
            $this->strategist->createForm(),
            'Mocked UseCaseFormFactory::createForm expected to return null'
        );
        $this->assertNull(
            $this->strategist->createForm(array('entity' => array('extra' => 'extraData'))),
            'Mocked UseCaseFormFactory::createForm expected to return null'
        );
    }
}
