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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\Abstracts\AbstractStrategistForm;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\UseCaseType;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\UseCaseFactory;

/**
 * UseCaseFormFactory.
 *
 * The UseCaseFormFactory create
 * strategic form for the UseCase
 * entity context.
 *
 * @category FormFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UseCaseFormFactory extends AbstractStrategistForm {
    
    /**
     * Use case factory
     * 
     * The use case factory
     * service
     * 
     * @var UseCaseFactory
     */
    protected $useCaseFactory;
    
    /**
     * Set UseCaseFactory
     * 
     * This method register the
     * use case factory to creating
     * UseCase entity instances.
     * 
     * @param UseCaseFactory $factory
     * 
     * @return UseCaseFormFactory
     */
    public function setUseCaseFactory(UseCaseFactory $factory){
        $this->useCaseFactory = $factory;
        return $this;
    }
    
    /**
     * Create form
     * 
     * This method create and 
     * return the form.
     * 
     * The building UseCase options
     * are defined into options
     * `entity` namespace. Must formed
     * as array(array("property"=>mixed, "data"=>mixed, "options"=>array), ...)
     * 
     * @param array $options The creation options
     * 
     * @return FormInterface
     * @see UseCaseBuilder
     */
    public function createForm($options = null){
        
        if ($options !== null && array_key_exists("entity", $options)) {
            $useCaseOptions = $options["entity"];
        } else {
            $useCaseOptions = array();
        }
        $data = $this->useCaseFactory->getInstance($useCaseOptions);
        
        $formType = new UseCaseType();
        $formType->setStrategy($this->getStrategy($this->defaultStrategy));
        
        $form = $this->formFactory->create($formType, $data);
        
        return $form;
    }
    
}