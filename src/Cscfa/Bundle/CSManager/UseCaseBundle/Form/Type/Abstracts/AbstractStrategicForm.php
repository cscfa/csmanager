<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Form
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Interfaces\StrategicForm;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\UseCaseStrategyInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * AbstractStrategicForm.
 *
 * The AbstractStrategicForm perform
 * the form creation from a specific
 * strategy.
 *
 * @category Form
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractStrategicForm extends AbstractType implements StrategicForm
{
    /**
     * The strategy.
     *
     * This property register
     * the strategy to use to
     * build the form.
     *
     * @var UseCaseStrategyInterface
     */
    protected $strategy;

    /**
     * Set strategy.
     *
     * This method allow to register
     * the create form strategy.
     *
     * @param UseCaseStrategyInterface $strategy The strategy to use
     *
     * @return UseCaseType
     */
    final public function setStrategy(UseCaseStrategyInterface $strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * BuildForm.
     *
     * This build the common
     * type form with the strategy
     * algorithm.
     *
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     *
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $this->strategy->buildForm($builder, $options);
    }
}
