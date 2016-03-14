<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category EntityFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCase;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Factories\Abstracts\AbstractBuilderFactory;

/**
 * UseCaseFactory class.
 *
 * The UseCaseFactory class
 * perform the creation of
 * UseCase objects.
 *
 * @category EntityFactory
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UseCaseFactory extends AbstractBuilderFactory {
    
    /**
     * Create new
     * 
     * This method create a new empty instance
     * 
     * @return UseCase
     */
    protected function createNew(){
        return new UseCase();
    }

    /**
     * Get instance
     *
     * This method return an instance
     * of the factory target.
     *
     * The option array must be
     * constitued of key/value that
     * key must be 'property' and the
     * value define the property to
     * build, according with the builder
     * responsibility chain.
     *
     * The options can contain other
     * couple of keys/values, but
     *they will not be used.
     *
     * @param array $options The creation options
     *
     * @return UseCase
     */
    public function getInstance(array $options = null){
        $this->builder->setEntity($this->createNew());
        
        if ($options !== null) {
            foreach ($options as $option) {
                
                if (!array_key_exists("property", $option)) {
                    continue;
                }
                
                $this->builder->add(
                    $option["property"], 
                    isset($option["data"])?$option["data"]:null,
                    isset($option["options"])?$option["options"]:array()
                );
            }
        }
        
        return $this->builder->getEntity();
    }

}
