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
namespace Cscfa\Bundle\CSManager\CoreBundle\Tests\Role;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;

/**
 * RoleProviderTest class.
 *
 * The RoleProviderTest class provide test to
 * valid RoleProvider methods process.
 * 
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
 */
class RoleProviderTest extends WebTestCase
{
    /**
     * The RoleProvider service
     * 
     * This service can be used to
     * get some Role instance from
     * the database.
     * 
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * The RoleManager service.
     * 
     * This service can be used to
     * process some validations on
     * Role instance.
     * 
     * @var RoleManager
     */
    protected $roleManager;

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
        
        $this->roleProvider = static::$kernel->getContainer()->get('core.provider.role_provider');
        $this->roleManager = static::$kernel->getContainer()->get('core.manager.role_manager');
        $this->doctrine = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * The testRoleProvider test.
     * 
     * This test is used to confirm
     * the RoleProvider service methods.
     * 
     * @return void
     */
    public function testRoleProvider()
    {
        $this->assertTrue($this->roleProvider instanceof RoleProvider, "Role provider is not an instance of RoleProvider");
        
        $role = new Role();
        $roleName = "ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180);
        $role->setCreatedAt(new \DateTime());
        $role->setName($roleName);
        
        $this->doctrine->persist($role);
        $this->doctrine->flush();
        
        $this->assertTrue($this->roleProvider->isExistingByName($roleName), "RoleProvider::isExistingByName() method return false on existing role.");
        $this->assertFalse($this->roleProvider->isExistingByName(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 160)), 0, 160)), "RoleProvider::isExistingByName() method return true on not existing role.");
        
        $roleRecup = $this->roleProvider->findOneByName($roleName);
        $roleNRecup = $this->roleProvider->findOneByName(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 160)), 0, 160));
        $this->assertTrue($roleRecup !== null, "RoleProvider::findOneByName() return null on existing role");
        $this->assertTrue($roleNRecup === null, "RoleProvider::findOneByName() return not null on not existing role");
        
        $allRoles = $this->roleProvider->findAll();
        $this->assertTrue($allRoles !== null, "RoleProvider::findAll() return null.");
        $this->assertTrue(is_array($allRoles), "RoleProvider::findAll() does not return an array.");
        $this->assertContains($role, $allRoles, "RoleProvider::findAll() result does not contain existing role.");
        $this->assertGreaterThanOrEqual(1, $allRoles, "The RoleProvider::findAll() method doesn't contain one index.");
        
        $rolesNames = $this->roleProvider->findAllNames();
        $this->assertTrue(is_array($rolesNames), "RoleProvider::getAllNames() doesn't return array.");
        $this->assertFalse(empty($rolesNames), "RoleProvider::getAllNames() return empty array.");
        $this->assertTrue(in_array($roleName, $rolesNames), "RoleProvider::getAllNames() doesn't return existing role name.");
        
        $this->doctrine->remove($role);
        $this->doctrine->flush();
    }
}
