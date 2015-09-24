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
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\SecurityBundle\Entity\StackUpdate;

/**
 * UserBuilderTest class.
 *
 * The UserBuilderTest class provide test to
 * valid UserBuilder methods process.
 *
 * @category Test
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\User
 */
class UserBuilderTest extends WebTestCase
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
     * The testBuilder test.
     *
     * This test is used to confirm
     * the UserBuilder service methods.
     *
     * @return void
     */
    public function testBuilder()
    {
        $userElm = new User();
        $userElm->setUsername("testBuilder")
            ->setUsernameCanonical("tesbuiler")
            ->setConfirmationToken(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180))
            ->setEmail("test.builder@test.ts")
            ->setEmailCanonical("test.builder@test.ts")
            ->setEnabled(false)
            ->setExpired(false)
            ->setSalt("salt")
            ->setPassword("testPassword")
            ->setPlainPassword("testPlainPassword")
            ->setLocked(false)
            ->setCredentialsExpired(false)
            ->setCreatedAt(new \DateTime());
        $this->doctrine->persist($userElm);
        $this->doctrine->flush();
        
        $dateNow = new \DateTime();
        $dateAfterNow = new \DateTime('@' . ($dateNow->getTimestamp() + 10000));
        $dateBeforeNow = new \DateTime('@' . ($dateNow->getTimestamp() - 10000));
        
        $user = $this->userManager->getNewInstance();
        
        $this->assertTrue($user->setUsername("matthieu"));
        $this->assertFalse($user->setUsername("matt hieu"));
        $this->assertTrue($user->getUsername() === "matthieu");
        $this->assertTrue($user->getLastError() === $user::INVALID_USERNAME);
        $this->assertTrue($user->setUsername("matt hieu", true));
        $this->assertTrue($user->getUsername() === "matt hieu");
        
        $this->assertTrue($user->setSalt("azerdfskjh"));
        $this->assertFalse($user->setSalt(true));
        $this->assertTrue($user->getSalt() === "azerdfskjh");
        $this->assertTrue($user->getLastError() === $user::IS_NOT_STRING);
        $this->assertTrue($user->setSalt(false, true));
        $this->assertTrue($user->getSalt() === false);
        
        $date = new \DateTime();
        $this->assertTrue($user->setPasswordRequestedAt($date));
        $this->assertFalse($user->setPasswordRequestedAt($dateAfterNow));
        $this->assertTrue($user->getPasswordRequestedAt() === $date);
        $this->assertTrue($user->getLastError() === $user::DATE_AFTER_NOW);
        $this->assertTrue($user->setPasswordRequestedAt(null));
        $this->assertTrue($user->setPasswordRequestedAt($dateAfterNow, true));
        $this->assertTrue($user->getPasswordRequestedAt() === $dateAfterNow);
        
        $this->assertFalse($user->setPassword(""));
        $this->assertTrue($user->getLastError() === $user::EMPTY_PASSWORD);
        $this->assertTrue($user->setPassword("zergqfg"));
        $this->assertFalse($user->setPassword(42));
        $this->assertTrue($user->getLastError() === $user::IS_NOT_STRING);
        $this->assertTrue($user->setPassword(42, true));
        
        $this->assertTrue($user->setLocked(true));
        $this->assertTrue($user->setLocked(false));
        $this->assertFalse($user->setLocked("hello"));
        $this->assertTrue($user->isLocked() === false);
        $this->assertTrue($user->getLastError() === $user::IS_NOT_BOOLEAN);
        $this->assertTrue($user->setLocked("world", true));
        $this->assertTrue($user->isLocked() === true);
        
        $date = new \DateTime();
        $this->assertTrue($user->setLastLogin($date));
        $this->assertFalse($user->setLastLogin($dateAfterNow));
        $this->assertTrue($user->getLastLogin() === $date);
        $this->assertTrue($user->getLastError() === $user::LAST_LOGIN_AFTER_NOW);
        $this->assertTrue($user->setLastLogin(null));
        $this->assertTrue($user->setLastLogin($dateAfterNow, true));
        $this->assertTrue($user->getLastLogin() === $dateAfterNow);
        
        $this->assertTrue($user->setExpiresAt(null));
        $this->assertFalse($user->setExpiresAt($dateBeforeNow));
        $this->assertTrue($user->getLastError() === $user::EXPIRATION_DATE_BEFORE_NOW);
        $this->assertTrue($user->setExpiresAt(new \DateTime()));
        $this->assertTrue($user->setExpiresAt($dateAfterNow));
        $this->assertTrue($user->setExpiresAt($dateBeforeNow, true));
        
        $this->assertTrue($user->setExpired(true));
        $this->assertTrue($user->setExpired(false));
        $this->assertFalse($user->setExpired("hello"));
        $this->assertTrue($user->isExpired() === false);
        $this->assertTrue($user->getLastError() === $user::IS_NOT_BOOLEAN);
        $this->assertTrue($user->setExpired("world", true));
        $this->assertTrue($user->isExpired() === true);
        
        $this->assertTrue($user->setEnabled(true));
        $this->assertTrue($user->setEnabled(false));
        $this->assertFalse($user->setEnabled("hello"));
        $this->assertTrue($user->isEnabled() === false);
        $this->assertTrue($user->getLastError() === $user::IS_NOT_BOOLEAN);
        $this->assertTrue($user->setEnabled("world", true));
        $this->assertTrue($user->isEnabled() === true);
        
        $this->assertTrue($user->setEmail("matthieu.vallance_test@cscfa.fr"));
        $this->assertFalse($user->setEmail("invalid"));
        $this->assertTrue($user->getEmail() === "matthieu.vallance_test@cscfa.fr");
        $this->assertTrue($user->getLastError() === $user::INVALID_EMAIL);
        $this->assertFalse($user->setEmail("test.builder@test.ts"));
        $this->assertTrue($user->getLastError() === $user::DUPLICATE_EMAIL);
        $this->assertTrue($user->setEmail("invalid", true));
        $this->assertTrue($user->getEmail() === "invalid");
        
        try {
            $this->assertFalse($user->setEmail("test@test.ts", true));
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        
        $this->doctrine->remove($userElm);
        $this->doctrine->flush();
        
        $this->assertTrue($user->setCredentialsExpired(true));
        $this->assertTrue($user->setCredentialsExpired(false));
        $this->assertFalse($user->setCredentialsExpired(null));
        $this->assertTrue($user->isCredentialsExpired() === false);
        $this->assertTrue($user->getLastError() === $user::IS_NOT_BOOLEAN);
        $this->assertFalse($user->setCredentialsExpired("invalid"));
        $this->assertTrue($user->getLastError() === $user::IS_NOT_BOOLEAN);
        $this->assertTrue($user->setCredentialsExpired("invalid", true));
        $this->assertTrue($user->isCredentialsExpired() === true);
        
        $this->assertFalse($user->setCredentialsExpireAt($dateBeforeNow));
        $this->assertTrue($user->getLastError() === $user::EXPIRATION_DATE_BEFORE_NOW);
        $this->assertTrue($user->setCredentialsExpireAt($dateAfterNow));
        $this->assertTrue($user->setCredentialsExpireAt(null));
        $this->assertTrue($user->setCredentialsExpireAt($dateBeforeNow, true));
        
        $token = substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180);
        $this->assertTrue($user->setConfirmationToken($token));
        $this->assertFalse($user->setConfirmationToken(148));
        $this->assertTrue($user->getConfirmationToken() === $token);
        $this->assertTrue($user->getLastError() === $user::IS_NOT_STRING);
        $this->assertTrue($user->setConfirmationToken(148, true));
        $this->assertTrue($user->setConfirmationToken(null));
        $this->assertTrue($user->getConfirmationToken() === null);
    }
}