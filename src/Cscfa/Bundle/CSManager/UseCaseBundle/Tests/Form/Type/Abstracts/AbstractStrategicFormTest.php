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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Form\Type\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Abstracts\AbstractStrategicForm;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\UseCaseStrategyInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;

/**
 * AbstractStrategicFormTest.
 *
 * The AbstractStrategicFormTest
 * process the AbstractStrategicForm
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Abstracts\AbstractStrategicForm
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Abstracts\AbstractStrategicForm
 */
class AbstractStrategicFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Strategic form.
     *
     * This property register the AbstractStrategicForm
     * mocked instance to be tested.
     *
     * @var AbstractStrategicForm|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $strategicForm;

    /**
     * Strategy.
     *
     * This property register the UseCaseStrategyInterface
     * mocked instance to be used in tests.
     *
     * @var UseCaseStrategyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $strategy;

    /**
     * Form builder.
     *
     * This property register the FormBuilderInterface
     * mocked instance to be used in tests.
     *
     * @var FormBuilderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $formBuilder;

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
        $this->strategicForm = $this->getMockForAbstractClass(AbstractStrategicForm::class);

        $this->strategy = $this->getMockForAbstractClass(UseCaseStrategyInterface::class);

        $this->formBuilder = $this->getMockforAbstractClass(FormBuilderInterface::class);
    }

    /**
     * Test use.
     *
     * This method process the test for the
     * AbstractStrategicForm instance.
     */
    public function testUse()
    {
        $this->strategy->expects($this->exactly(2))
            ->method('buildForm');
        $this->strategy->expects($this->at(0))
            ->method('buildForm')
            ->with($this->identicalTo($this->formBuilder), $this->equalTo(array()));
        $this->strategy->expects($this->at(1))
            ->method('buildForm')
            ->with($this->identicalTo($this->formBuilder), $this->equalTo(array(1, 2, 3)));

        $this->strategicForm->setStrategy($this->strategy);
        $this->strategicForm->buildForm($this->formBuilder);
        $this->strategicForm->buildForm($this->formBuilder, array(1, 2, 3));
    }
}
