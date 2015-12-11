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
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\UserBundle\Entity\Type");
        $workType = $repository->findOneByLabel("work");
        if($workType){
            $work = $workType;
        }else{
            $work = new Type();
            $work->setLabel("work");
            $manager->persist($work);
        }

        $personnalType = $repository->findOneByLabel("personnal");
        if($personnalType){
            $personnal = $personnalType;
        }else{
            $personnal = new Type();
            $personnal->setLabel("personnal");
            $manager->persist($personnal);
        }

        $alternativeType = $repository->findOneByLabel("alternative");
        if($alternativeType){
            $alternative = $alternativeType;
        }else{
            $alternative = new Type();
            $alternative->setLabel("alternative");
            $manager->persist($alternative);
        }
        
        $manager->flush();
    }
}
