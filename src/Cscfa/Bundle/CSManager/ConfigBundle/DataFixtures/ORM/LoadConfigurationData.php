<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Fixture
 * @package  CscfaCSManagerConfigBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ConfigBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cscfa\Bundle\CSManager\ConfigBundle\Entity\Configuration;
use Cscfa\Bundle\CSManager\ConfigBundle\Entity\Preference;

/**
 * LoadConfigurationData class.
 *
 * The LoadConfigurationData provide
 * method to load the configuration data.
 *
 * @category Fixture
 * @package  CscfaCSManagerConfigBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class LoadConfigurationData implements FixtureInterface
{

    /**
     * Load
     * 
     * Load the configuration data
     * 
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        
        $configuration = $manager->getRepository("Cscfa\Bundle\CSManager\ConfigBundle\Entity\Configuration")
            ->findOneByName("default");
        
        if(!$configuration){
            $configuration = new Configuration();
        }
        $configuration->setName("default");
        
        $manager->persist($configuration);

        $preference = $manager->getRepository("Cscfa\Bundle\CSManager\ConfigBundle\Entity\Preference")
            ->findAll();
        
        if(!$preference){
            $preference = new Preference();
        }else if(is_array($preference)){
            $preference = $preference[0];
        }
        $preference->setConfiguration($configuration);
        
        $manager->persist($preference);
        $manager->flush();
    }
}