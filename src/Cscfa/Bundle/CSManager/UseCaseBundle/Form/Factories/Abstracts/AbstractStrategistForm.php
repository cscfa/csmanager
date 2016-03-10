<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category FormFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Interfaces\UseCaseFormFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Interfaces\StrategistFormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\FormStrategyInterface;

/**
 * AbstractStrategistForm class.
 *
 * The AbstractStrategistForm
 * define the use case formFactories
 * methods for strategist forms.
 *
 * @category FormFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class AbstractStrategistForm implements UseCaseFormFactoryInterface, StrategistFormInterface {
    
    /**
     * Form factory
     * 
     * The form factory to use
     * to create the form
     * 
     * @var FormFactoryInterface
     */
    protected $formFactory;
    
    /**
     * Strategies
     * 
     * The stored strategies
     * 
     * @var array:[ string => FormStrategyInterface ]
     */
    protected $strategies;
    
    /**
     * Default strategy
     * 
     * The strategy to use on form creating
     * 
     * @var string
     */
    protected $defaultStrategy;
    
    /**
     * Set form factory
     * 
     * This method set the form
     * factory service.
     * 
     * @param FormFactoryInterface $formFactory The form factory service
     * 
     * @return UseCaseFormFactoryInterface
     */
    public function setFormFactory(FormFactoryInterface $formFactory) {
        $this->formFactory = $formFactory;
        return $this;
    }
    
    /**
     * Get form factory
     * 
     * This method return the
     * form factory service.
     * 
     * @return FormFactoryInterface
     */
    public function getFormFactory() {
        return $this->formFactory;
    }

    /**
     * Create form
     *
     * This method create and
     * return the form.
     *
     * @param array $options The creation options
     * 
     * @return FormInterface
     */
    abstract public function createForm($options = null);
    
    /**
     * Add strategy
     * 
     * This method register a
     * strategy with it alias 
     * name to be pulled later.
     * 
     * @param FormStrategyInterface $strategy The strategy
     * @param string                $alias    The alias name
     * 
     * @return StrategistFormInterface
     */
    public function addStrategy(FormStrategyInterface $strategy, $alias) {
        $this->strategies[$alias] = $strategy;
        return $this;
    }
    
    /**
     * Get strategy
     * 
     * This method return a
     * strategy selected by it
     * alias name.
     * 
     * @param string $alias The alias name
     * 
     * @return FormStrategyInterface|null
     */
    protected function getStrategy($alias) {
        if ($this->hasStrategy($alias)) {
            return $this->strategies[$alias];
        } else {
            return null;
        }
    }
    
    /**
     * Use strategy
     * 
     * This method define the
     * strategy to use when
     * creating the form.
     * 
     * @param string $alias The alias name
     * 
     * @return StrategistFormInterface
     */
    public function useStrategy($alias) {
        $this->defaultStrategy = $alias;
        return $this;
    }
    
    /**
     * Has strategy
     * 
     * This method return true
     * if the strategy designed
     * by the given alias name.
     * 
     * @param string $alias The alias name
     * 
     * @return boolean
     */
    public function hasStrategy($alias) {
        return array_key_exists($alias, $this->strategies);
    }
}
