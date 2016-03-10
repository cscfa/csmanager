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
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Abstracts;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\UseCaseStrategyInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AbstractUseCaseStrategy class.
 *
 * The AbstractUseCaseStrategy class
 * perform the building of form for
 * UseCase type.
 *
 * @category FormStrategy
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class AbstractUseCaseStrategy implements UseCaseStrategyInterface {
    
    /**
     * The translation domain
     * 
     * This property register
     * the translation domain
     * 
     * @var string
     */
    protected $transDomain;
    
    /**
     * The translation service
     * 
     * This property register
     * the translation service
     * 
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Building the form
     *
     * This method perform the
     * form type building.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The building options
     */
    public abstract function buildForm(FormBuilderInterface $builder, array $options);
    
    /**
     * Get translation domain
     * 
     * This method return the
     * translation domain.
     * 
     * @return string
     */
    public function getTransDomain() {
        return $this->transDomain;
    }
    
    /**
     * Set translation domain
     * 
     * This method allow to define
     * the translation domain to
     * use into the translator
     * service.
     * 
     * @param string $transDomain The translation domain
     * 
     * @return UseCaseStrategyInterface
     */
    public function setTransDomain($transDomain) {
        $this->transDomain = $transDomain;
        return $this;
    }

    /**
     * Get translator
     *
     * This method return the
     * translation service.
     */
    public function getTranslator() {
        return $this->translator;
    }
    
    /**
     * Set translator
     * 
     * This method allow to store
     * the translation service.
     * 
     * @param TranslatorInterface $translator The translator service
     * 
     * @return UseCaseStrategyInterface
     */
    public function setTranslator(TranslatorInterface $translator) {
        $this->translator = $translator;
        return $this;
    }
}
