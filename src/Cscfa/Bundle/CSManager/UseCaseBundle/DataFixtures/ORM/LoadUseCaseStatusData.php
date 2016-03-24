<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Fixture
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCaseStatus;

/**
 * LoadUseCaseStatusData.
 *
 * The LoadUseCaseStatusData is used
 * to load the default use case status.
 *
 * @category Fixture
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class LoadUseCaseStatusData implements FixtureInterface
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
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCaseStatus");

        $names = array(
            'active' => 'The use case is currently active',
            'closed' => 'The use case is currently closed',
        );
        foreach ($names as $name => $description) {
            $status = $repository->findOneByName($name);
            if ($status) {
                $entity = $status;
            } else {
                $entity = new UseCaseStatus();
                $entity->setName($name);
                $entity->setDescription($description);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }
}
