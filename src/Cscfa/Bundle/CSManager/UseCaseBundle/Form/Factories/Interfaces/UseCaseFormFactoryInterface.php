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

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * UseCaseFormFactoryInterface interface.
 *
 * The UseCaseFormFactoryInterface
 * define the bundle formFactories
 * methods.
 *
 * @category FormFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface UseCaseFormFactoryInterface {
    
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
    public function setFormFactory(FormFactoryInterface $formFactory);
    
    /**
     * Get form factory
     * 
     * This method return the
     * form factory service.
     * 
     * @return FormFactoryInterface
     */
    public function getFormFactory();
    
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
    public function createForm($options = null);
    
}
