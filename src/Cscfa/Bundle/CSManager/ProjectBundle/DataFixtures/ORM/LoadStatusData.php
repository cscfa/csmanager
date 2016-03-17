<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Fixture
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectStatus;

/**
 * LoadStatusData class.
 *
 * The LoadStatusData provide
 * method to load the project
 * status data.
 *
 * @category Fixture
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class LoadStatusData implements FixtureInterface
{
    /**
     * Load.
     *
     * This method allow to load
     * the project status data into
     * the database.
     *
     * @param ObjectManager $manager The doctrine object manager
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectStatus");

        $names = array('active', 'closed');
        foreach ($names as $name) {
            $status = $repository->findOneByName($name);
            if ($status) {
                $entity = $status;
            } else {
                $entity = new ProjectStatus();
                $entity->setName($name);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }
}
