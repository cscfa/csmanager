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
 * @package  CscfaCSManagerUserBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Type;

/**
 * LoadTypeData class.
 *
 * The LoadTypeData provide
 * method to load the type data.
 *
 * @category Fixture
 * @package  CscfaCSManagerUserBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class LoadTypeData implements FixtureInterface
{
    /**
     * Load
     * 
     * This method allow to load
     * the type data into the
     * database.
     * 
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\UserBundle\Entity\Type");

        $labels = array("work", "personnal", "alternative");
        foreach ($labels as $label) {
            $type = $repository->findOneByLabel($label);
            if($type){
                $entity = $type;
            }else{
                $entity = new Type();
                $entity->setLabel($label);
                $manager->persist($entity);
            }
        }
        
        $manager->flush();
    }
}
