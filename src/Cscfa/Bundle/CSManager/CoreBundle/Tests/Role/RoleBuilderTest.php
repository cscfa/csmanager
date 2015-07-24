<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Test
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Tests\Role;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;

/**
 * RoleBuilderTest class.
 *
 * The RoleBuilderTest class provide test to
 * valid RoleBuilder methods process.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
 */
class RoleBuilderTest extends WebTestCase
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
     * The testChildSetter test.
     * 
     * This test is used to confirm
     * the RoleBuilder child setter
     * method.
     * 
     * @return void
     */
    public function testChildSetter()
    {
        $roleBuilder = new RoleBuilder($this->roleManager, null);
        $utilRole = new Role();
        $crRole1 = new Role();
        $crRole2 = new Role();
        
        $utilRole->setName("ROLE_TEST");
        $crRole1->setChild($crRole2);
        $crRole2->setChild($crRole1);
        
        $this->assertTrue($roleBuilder->setChild($utilRole), "RoleBuilder return false when setChild() insert a Role type without circular reference.");
        $this->assertTrue($roleBuilder->setChild(null), "RoleBuilder return false when setChild() insert null.");
        
        $this->assertFalse($roleBuilder->setChild(12), "RoleBuilder return true when setChild() insert an invalid type.");
        $this->assertEquals(RoleBuilder::INVALID_ROLE_INSTANCE_OF, $roleBuilder->getLastError(), "RoleBuilder lastError() return invalid value when setChild() insert an invalid type.");
        $this->assertFalse($roleBuilder->setChild($crRole1), "RoleBuilder return true when setChild() insert a Role type with circular reference.");
        $this->assertEquals(RoleBuilder::CIRCULAR_REFERENCE, $roleBuilder->getLastError(), "RoleBuilder lastError() return invalid value when setChild() insert a Role type with circular reference.");
        
        $this->assertTrue($roleBuilder->setChild(12, true), "RoleBuilder return false when setChild() insert an invalid type with force.");
        $this->assertTrue($roleBuilder->setChild($crRole1, true), "RoleBuilder return false when setChild() insert a Role type with circular reference.");
        $this->assertTrue($roleBuilder->setChild(null), "RoleBuilder return false when setChild() insert null.");
    }

    /**
     * The testDateSetters test.
     * 
     * This test is used to confirm
     * the RoleBuilder date setters
     * method.
     * 
     * @return void
     */
    public function testDateSetters()
    {
        $roleBuilder = new RoleBuilder($this->roleManager, null);
        
        $dateNow = new \DateTime();
        $dateAfterNow = new \DateTime('@' . ($dateNow->getTimestamp() + 10000));
        $dateNowMin = new \DateTime('@' . ($dateNow->getTimestamp() - 1000));
        $dateBeforeNow = new \DateTime('@' . ($dateNow->getTimestamp() - 10000));
        
        $this->assertFalse($roleBuilder->setCreatedAt($dateAfterNow), "RoleBuilder return true when passing date after now to setCreationAt().");
        $this->assertEquals(RoleBuilder::CREATION_AFTER_NOW, $roleBuilder->getLastError(), "RoleBuilder lastError() return invalid value when passing date after now to setCreationAt().");
        $this->assertTrue($roleBuilder->setCreatedAt($dateNowMin), "RoleBuilder return false when passing a valid date to setCreationDate().");
        $this->assertFalse($roleBuilder->setUpdatedAt($dateBeforeNow), "RoleBuilder return true when passing a date before creation date to setUpdatedAt().");
        $this->assertEquals(RoleBuilder::UPDATE_BEFORE_CREATION, $roleBuilder->getLastError(), "RoleBuilder lastError() return invalid value when passing a date before creation date to setUpdatedAt().");
        $this->assertFalse($roleBuilder->setUpdatedAt($dateAfterNow), "RoleBuilder return true when passing a date after now to setUpdatedAt().");
        $this->assertEquals(RoleBuilder::UPDATE_AFTER_NOW, $roleBuilder->getLastError(), "RoleBuilder lastError() return invalid value when passing a date after now to setUpdatedAt().");
        $this->assertTrue($roleBuilder->setUpdatedAt($dateNow), "RoleBuilder return false when passing a valid date to setUpdatedAt().");
        
        $this->assertTrue($roleBuilder->setCreatedAt($dateAfterNow, true), "RoleBuilder return false when passing date after now to setCreationAt() and force.");
        $this->assertTrue($roleBuilder->setUpdatedAt($dateBeforeNow, true), "RoleBuilder return false when passing a date before creation date to setUpdatedAt() and force.");
        $this->assertTrue($roleBuilder->setUpdatedAt($dateAfterNow, true), "RoleBuilder return false when passing a date after now to setUpdatedAt() and force.");
    }

    /**
     * The testNameSetter test.
     * 
     * This test is used to confirm
     * the RoleBuilder name setter
     * method.
     * 
     * @return void
     */
    public function testNameSetter()
    {
        $role = new Role();
        $role->setCreatedAt(new \DateTime());
        $role->setName("ROLE_TEST");
        $roleBuilder = new RoleBuilder($this->roleManager, null);
        $roleNameExist = $this->roleManager->getRolesName();
        $roleNameCount = count($roleNameExist);
        if ($roleNameCount > 0) {
            $roleNameExist = $roleNameExist[0];
        } else {
            $roleNameExist = "ROLE_TEST";
            $this->doctrine->persist($role);
            $this->doctrine->flush();
        }
        
        $this->assertFalse($roleBuilder->setName($roleNameExist), "RoleBuilder return true when passing an existing name to setName().");
        $this->assertTrue($roleBuilder->setName("ROLE_" . substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180)), "RoleBuilder return false when passing a not existing name to setName().");
        
        $this->assertTrue($roleBuilder->setName($roleNameExist, true), "RoleBuilder return false when passing an existing name to setName() and force.");
        
        if ($roleNameCount == 0) {
            $this->doctrine->remove($role);
            $this->doctrine->flush();
        }
    }
}
