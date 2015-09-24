<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Test
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Tests\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;

/**
 * UserManagerTest class.
 *
 * The UserManagerTest class provide test to
 * valid UserManager methods process.
 *
 * @category Test
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\User
 */
class UserManagerTest extends WebTestCase
{

    /**
     * The user manager service.
     * 
     * This service is the main 
     * tested class. It provide
     * abstraction to user 
     * validation and logical
     * management.
     * 
     * @var UserManager
     */
    protected $userManager;

    /**
     * The doctrine orm entity maager service.
     * 
     * This service can be used to manage the
     * database.
     * 
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * The setUp.
     * 
     * This method is used to configure
     * and process the initialisation of
     * the test class.
     * 
     * @return void
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        $this->userManager = static::$kernel->getContainer()->get('core.manager.user_manager');
        $this->doctrine = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * The testManager test.
     *
     * This test is used to confirm
     * the UserManager service methods.
     *
     * @return void
     */
    public function testManager()
    {
        
        $roleManager = $this->userManager->getRoleManager();
        $this->assertTrue($roleManager instanceof RoleManager);
        
        $this->assertTrue($this->userManager->isUsernameValid("myUsername"));
        $this->assertFalse($this->userManager->isUsernameValid("invalid name"));
        $this->assertTrue($this->userManager->isUsernameValid("myUsername43"));
        $this->assertFalse($this->userManager->isUsernameValid("68"));

        $this->assertTrue($this->userManager->isEmailValid("matthieu.vallance@cscfa.fr"));
        $this->assertFalse($this->userManager->isEmailValid("invalid email"));
        
        $user = new User();
        
        $user->setUsername("testManager")
            ->setUsernameCanonical("testmanager")
            ->setConfirmationToken(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180))
            ->setEmail("test.manager@test.ts")
            ->setEmailCanonical("test.manager@test.ts")
            ->setEnabled(false)
            ->setExpired(false)
            ->setSalt("salt")
            ->setPassword("testPassword")
            ->setPlainPassword("testPlainPassword")
            ->setLocked(false)
            ->setCredentialsExpired(false)
            ->setCreatedAt(new \DateTime());

        $convertedInstance = $this->userManager->convertInstance($user);
        $this->assertTrue($convertedInstance instanceof UserBuilder);
        
        $newInstance = $this->userManager->getNewInstance();
        $this->assertTrue($newInstance instanceof UserBuilder);
        
        $this->userManager->persist($convertedInstance);
        $this->assertTrue($this->doctrine->find("Cscfa\Bundle\SecurityBundle\Entity\User", $convertedInstance->getUser()->getId()) !== null);
        
        $this->userManager->remove($convertedInstance);
        $this->assertTrue($convertedInstance->getUser()->getId() === null);
    }
}