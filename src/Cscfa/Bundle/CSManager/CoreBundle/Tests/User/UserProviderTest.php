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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Tests\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder;

/**
 * UserProviderTest class.
 *
 * The UserProviderTest class provide test to
 * valid UserProvider methods process.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class UserProviderTest extends WebTestCase
{

    /**
     * The user provider service.
     * 
     * This service is the main 
     * tested class. It provide
     * abstraction to user access
     * into database.
     * 
     * @var UserProvider
     */
    protected $userProvider;

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
        
        $this->userProvider = static::$kernel->getContainer()->get('core.provider.user_provider');
        $this->doctrine = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * The testProvider test.
     * 
     * This test is used to confirm
     * the UserProvider service methods.
     * 
     * @return void
     */
    public function testProvider()
    {
        $user = new User();
        
        $user->setUsername("test")
            ->setUsernameCanonical("test")
            ->setConfirmationToken(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180))
            ->setEmail("test@test.ts")
            ->setEmailCanonical("test@test.ts")
            ->setEnabled(false)
            ->setExpired(false)
            ->setSalt("salt")
            ->setPassword("testPassword")
            ->setPlainPassword("testPlainPassword")
            ->setLocked(false)
            ->setCredentialsExpired(false)
            ->setCreatedAt(new \DateTime());
        
        $this->doctrine->persist($user);
        $this->doctrine->flush();
        
        $emails = $this->userProvider->getAllEmail();
        $this->assertTrue(in_array($user->getEmailCanonical(), $emails));
        
        $usernames = $this->userProvider->getAllUsernames();
        $this->assertTrue(in_array($user->getUsernameCanonical(), $usernames));
        
        $retreivedUser = $this->userProvider->findOneByUsername($user->getUsername());
        $this->assertTrue($retreivedUser instanceof UserBuilder);
        $this->assertTrue($retreivedUser->getUser()->getId() === $user->getId());
        
        $retreivedUser = null;
        $retreivedUser = $this->userProvider->findOneByEmail($user->getEmail());
        $this->assertTrue($retreivedUser instanceof UserBuilder);
        $this->assertTrue($retreivedUser->getUser()->getId() === $user->getId());
        
        $this->doctrine->remove($user);
        $this->doctrine->flush();
    }
}