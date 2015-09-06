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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;

/**
 * RoleManagerTest class.
 *
 * The RoleManagerTest class provide test to
 * valid RoleManager methods process.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
 */
class RoleManagerTest extends WebTestCase
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
     * The testStartRoleManager test.
     * 
     * This test is used to confirm
     * the RoleManager service instance 
     * type.
     * 
     * @return void
     */
    public function testStartRoleManager()
    {
        $this->assertTrue($this->roleManager instanceof RoleManager, "Role manager is not an instance of RoleManager.");
    }

    /**
     * The testNameIsValid test.
     * 
     * This test is used to confirm
     * the RoleProvider name validation 
     * methods process.
     * 
     * @return void
     */
    public function testNameIsValid()
    {
        $workingRoleName = "ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 120)), 0, 120);
        $this->assertTrue($this->roleManager->nameIsValid($workingRoleName), "The string validation faild on correct string");
        $notWorkingRoleName = "ROLE_" . openssl_random_pseudo_bytes(120);
        $this->assertFalse($this->roleManager->nameIsValid($notWorkingRoleName), "The string validation success on incorrect string");
    }

    /**
     * The testNewInstance test.
     * 
     * This test is used to confirm
     * the RoleProvider Role instance 
     * creation process.
     * 
     * @return void
     */
    public function testNewInstance()
    {
        $roleBuilder = $this->roleManager->getNewInstance();
        $this->assertInstanceOf("Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder", $roleBuilder, "The result of RoleManager get new instance is not an instance of RoleBuilder");
        if ($roleBuilder instanceof RoleBuilder) {
            $this->assertTrue($roleBuilder->getRole() instanceof Role, "The result of RoleBuilder::getRole() is not an instance of Role");
            $this->assertNull($roleBuilder->getStackUpdate(), "The result of RoleBuilder::getStackUpdate() for empty creation is not null");
        }
    }

    /**
     * The testInstanceConvertion test.
     * 
     * This test is used to confirm
     * the RoleProvider Role instance
     * convertion methods process.
     * 
     * @return void
     */
    public function testInstanceConvertion()
    {
        $workingRoleName = "ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 120)), 0, 120);
        $role = new Role();
        $role->setName($workingRoleName);
        $roleConverted = $this->roleManager->convertInstance($role);
        $this->assertInstanceOf("Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder", $roleConverted, "The result of RoleManager::convertInstance is not a RoleBuilder instance");
        if ($roleConverted instanceof RoleBuilder) {
            $this->assertInstanceOf("Cscfa\Bundle\CSManager\CoreBundle\Entity\Role", $roleConverted->getRole(), "The result of RoleBuilder::getRole() is not an instance of Role for RoleManager::convertInstance RoleBuilder result");
            $this->assertInstanceOf("Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate", $roleConverted->getStackUpdate(), "The result of RoleBuilder::getStackUpdate() is not a StackUpdate instance for RoleManager::convertInstance RoleBuilder result");
        }
    }

    /**
     * The testCircularReference test.
     * 
     * This test is used to confirm
     * the RoleProvider Role instance
     * circular reference test methods 
     * process.
     * 
     * @return void
     */
    public function testCircularReference()
    {
        $roleCircular1 = new Role();
        $roleCircular2 = new Role();
        $role = new Role();
        $roleConverted = $this->roleManager->convertInstance($role);
        $roleCircular1->setChild($roleCircular2);
        $roleCircular2->setChild($roleCircular1);
        $this->assertTrue($this->roleManager->hasCircularReference($roleCircular1), "Circular reference not detected by RoleManager");
        $this->assertFalse($this->roleManager->hasCircularReference($roleConverted), "Circular reference detected by RoleManager but inexistant");
    }

    /**
     * The testPresistance test.
     * 
     * This test is used to confirm
     * the RoleProvider Role instance
     * database based methods process.
     * 
     * @return void
     */
    public function testPresistance()
    {
        $role1 = new Role();
        $role2 = new Role();
        $roleName1 = "ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180);
        $roleName2 = "ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 181)), 0, 181);
        $role1->setName($roleName1);
        $role2->setName($roleName2);
        $role1->setCreatedAt(new \DateTime());
        $role2->setCreatedAt(new \DateTime());
        $role2->setChild($role1);
        
        $role1 = $this->roleManager->convertInstance($role1);
        $role2 = $this->roleManager->convertInstance($role2);
        
        try {
            $this->roleManager->persist($role1);
            $this->roleManager->persist($role2);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, "The RoleManager::persist() method fail. Doctrine response : " . $e->getCode() . " " . $e->getMessage());
        }
        
        $rolesName = $this->roleManager->getRolesName();
        $this->assertTrue(is_array($rolesName), "The RoleManager::getRolesName() method doesn't return an array.");
        $this->assertContains($roleName1, $rolesName, "The RoleManager::getRolesName() method doesn't return first role name.");
        $this->assertContains($roleName2, $rolesName, "The RoleManager::getRolesName() method doesn't return second role name.");
        $this->assertContainsOnly('string', $rolesName, "The RoleManager::getRolesName() method result array doesn't contain only string.");
        $this->assertGreaterThanOrEqual(2, $rolesName, "The RoleManager::getRolesName() method doesn't contain two index.");
        
        $this->assertTrue($this->roleManager->roleExists($roleName1), "The RoleManager::roleExists() method fail on existing first role.");
        $this->assertTrue($this->roleManager->roleExists($roleName2), "The RoleManager::roleExists() method fail on existing second role.");
        $this->assertFalse($this->roleManager->roleExists(substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 182)), 0, 182)), "The RoleManager::roleExists() method success on non existing role.");
        
        $rolesWire = $this->roleManager->getRoleWire($roleName2);
        $this->assertTrue(is_array($rolesWire), "The RoleManager::getRoleWire() method doesn't return an array.");
        $this->assertContains($roleName1, $rolesWire, "The RoleManager::getRoleWire() method doesn't return first role name.");
        $this->assertContains($roleName2, $rolesWire, "The RoleManager::getRoleWire() method doesn't return second role name.");
        $this->assertContainsOnly('string', $rolesWire, "The RoleManager::getRoleWire() method result array doesn't contain only string.");
        $this->assertGreaterThanOrEqual(2, $rolesWire, "The RoleManager::getRoleWire() method doesn't contain two index.");
        
        try {
            $this->roleManager->remove($role2);
            $this->roleManager->remove($role1);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, "The RoleManager::persist() method fail. Doctrine response : " . $e->getCode() . " " . $e->getMessage());
        }
    }
}
