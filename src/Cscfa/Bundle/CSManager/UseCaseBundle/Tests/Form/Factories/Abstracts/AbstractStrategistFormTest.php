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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Tests\Form\Factories\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Abstracts\AbstractStrategistForm;
use Symfony\Component\Form\FormFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\FormStrategyInterface;

/**
 * AbstractStrategistFormTest.
 *
 * The AbstractStrategistFormTest
 * process the AbstractStrategistForm
 * tests.
 *
 * @see      Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Abstracts\AbstractStrategistForm
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @covers Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Abstracts\AbstractStrategistForm
 */
class AbstractStrategistFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Strategist.
     *
     * This property register the AbstractStrategistForm
     * mocked instance to be tested.
     *
     * @var AbstractStrategistForm|\PHPUnit_Framework_MockObject_MockObject
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
     * Set up.
     *
     * This method set up the test class before
     * tests.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->strategist = $this->getMockForAbstractClass(AbstractStrategistForm::class);

        $this->formFactory = $this->getMockForAbstractClass(FormFactoryInterface::class);
    }

    /**
     * Test form factory.
     *
     * This method test the AbstractStrategistForm
     * support methods.
     */
    public function testFormFactory()
    {
        $this->strategist->setFormFactory($this->formFactory);
        $this->assertEquals(
            $this->formFactory,
            $this->strategist->getFormFactory(),
            'The AbstractStrategistForm::getFormFactory must return the stored FormFactoryInterface'
        );
    }

    /**
     * Strategy provider.
     *
     * This method provide a set of
     * form strategy interface with
     * alias.
     */
    public function strategyProvider()
    {
        $base = array();

        for ($i = 0; $i < 20; ++$i) {
            array_push(
                $base,
                array(
                    $this->getMockForAbstractClass(FormStrategyInterface::class),
                    openssl_random_pseudo_bytes(mt_rand(5, 10)),
                    openssl_random_pseudo_bytes(mt_rand(5, 10)),
                )
            );
        }

        return $base;
    }

    /**
     * Test strategy.
     *
     * This method test the AbstractStrategistForm
     * support strategy.
     *
     * @param FormStrategyInterface $strategy   A strategy to register
     * @param string                $alias      The strategy alias
     * @param string                $wrongAlias An undefined alias
     *
     * @dataProvider strategyProvider
     */
    public function testStrategy($strategy, $alias, $wrongAlias)
    {
        $this->strategist->addStrategy($strategy, $alias);
        $this->assertTrue(
            $this->strategist->hasStrategy($alias),
            'The AbstractStrategistForm::hasStrategy must return true if the aliased strategy is stored'
        );
        $this->assertFalse(
            $this->strategist->hasStrategy($wrongAlias),
            'The AbstractStrategistForm::hasStrategy must return false if the aliased strategy is not stored'
        );
        $this->strategist->useStrategy($alias);
    }
}
