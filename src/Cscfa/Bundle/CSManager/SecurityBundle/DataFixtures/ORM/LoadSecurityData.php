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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cscfa\Bundle\SecurityBundle\Entity\Role;

/**
 * LoadSecurityData class.
 *
 * The LoadSecurityData provide
 * method to load the security data.
 *
 * @category Fixture
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class LoadSecurityData implements FixtureInterface
{
    /**
     * Load.
     *
     * Load the security data
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $user = new Role();
        $userManager = new Role();
        $admin = new Role();
        $superAdmin = new Role();

        $repository = $manager->getRepository("Cscfa\Bundle\SecurityBundle\Entity\Role");

        $roleUser = $repository->findOneByName('ROLE_USER');
        if ($roleUser) {
            $user = $roleUser;
        } else {
            $user->setName('ROLE_USER')
                ->setCreatedAt(new \DateTime());
            $manager->persist($user);
        }

        $roleManager = $repository->findOneByName('ROLE_MANAGER');
        if ($roleManager) {
            $userManager = $roleManager;
        } else {
            $userManager->setName('ROLE_MANAGER')
                ->setChild($user)
                ->setCreatedAt(new \DateTime());
            $manager->persist($userManager);
        }

        $roleAdmin = $repository->findOneByName('ROLE_ADMIN');
        if ($roleAdmin) {
            $admin = $roleAdmin;
        } else {
            $admin->setName('ROLE_ADMIN')
                ->setChild($userManager)
                ->setCreatedAt(new \DateTime());
            $manager->persist($admin);
        }

        $roleSuperAdmin = $repository->findOneByName('ROLE_SUPER_ADMIN');
        if ($roleSuperAdmin) {
            $superAdmin = $roleSuperAdmin;
        } else {
            $superAdmin->setName('ROLE_SUPER_ADMIN')
                ->setChild($admin)
                ->setCreatedAt(new \DateTime());
            $manager->persist($superAdmin);
        }

        $manager->flush();
    }
}
