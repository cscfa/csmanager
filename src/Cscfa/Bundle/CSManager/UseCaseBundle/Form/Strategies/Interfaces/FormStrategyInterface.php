<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category FormStrategy
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * FormStrategy interface.
 *
 * The base FormStrategy perform
 * the building of form.
 *
 * @category FormStrategy
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface FormStrategyInterface {
    
    /**
     * Building the form
     * 
     * This method perform the
     * form type building.
     * 
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The building options
     */
    public function buildForm(FormBuilderInterface $builder, array $options);
    
}
