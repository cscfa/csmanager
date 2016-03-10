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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Interfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\FormStrategyInterface;

/**
 * StrategistFormInterface interface.
 *
 * The StrategistFormInterface
 * define the strategist formFactories
 * methods.
 *
 * @category FormFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface StrategistFormInterface {
    
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
    public function addStrategy(FormStrategyInterface $strategy, $alias);
    
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
    public function hasStrategy($alias);
    
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
    public function useStrategy($alias);
    
}
