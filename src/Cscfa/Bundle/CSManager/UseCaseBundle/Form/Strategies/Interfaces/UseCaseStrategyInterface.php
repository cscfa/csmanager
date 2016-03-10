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

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\FormStrategyInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * UseCaseStrategyInterface interface.
 *
 * The base UseCaseStrategyInterface
 * perform the building of form for
 * UseCase type.
 *
 * @category FormStrategy
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface UseCaseStrategyInterface extends FormStrategyInterface {

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
    public function setTranslator(TranslatorInterface $translator);
    
    /**
     * Get translator
     * 
     * This method return the
     * translation service.
     */
    public function getTranslator();
    
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
    public function setTransDomain($transDomain);
    
    /**
     * Get translation domain
     * 
     * This method return the
     * translation domain.
     * 
     * @return string
     */
    public function getTransDomain();
    
}
