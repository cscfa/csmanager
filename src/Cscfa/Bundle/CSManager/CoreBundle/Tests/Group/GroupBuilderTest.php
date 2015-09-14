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
namespace Cscfa\Bundle\CSManager\CoreBundle\Tests\Group;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Group;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\GroupBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;

/**
 * GroupBuilderTest class.
 *
 * The GroupBuilderTest class provide test to
 * valid GroupBuilder methods process.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class GroupBuilderTest extends WebTestCase
{

    /**
     * The group manager service.
     *
     * This service is the main
     * tested class. It provide
     * abstraction to group 
     * instance management.
     *
     * @var GroupManager
     */
    protected $groupManager;

    /**
     * The role manager service.
     *
     * This service provide
     * abstraction to role
     * instance management.
     *
     * @var RoleManager
     */
    protected $roleManager;

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
        
        $this->groupManager = static::$kernel->getContainer()->get('core.manager.group_manager');
        $this->roleManager = static::$kernel->getContainer()->get('core.manager.role_manager');
    }

    /**
     * The testBuilder test.
     *
     * This test is used to confirm
     * the GroupBuilder service methods.
     *
     * @return void
     */
    public function testBuilder()
    {
        $builder = $this->groupManager->getNewInstance();
        $dateNow = new \DateTime();
        $dateAfterNow = new \DateTime('@' . ($dateNow->getTimestamp() + 10000));
        $dateBeforeNow = new \DateTime('@' . ($dateNow->getTimestamp() - 10000));
        
        $this->assertTrue($builder->getGroup() instanceof Group);
        $this->assertTrue($builder->getStackUpdate() === null);
        
        $builder = $this->groupManager->convertInstance($builder->getGroup());
        $this->assertTrue($builder->getStackUpdate() instanceof StackUpdate);
        
        $this->assertTrue($builder->setName("test"));
        $group = new Group();
        $group->setName("test21");
        $group->setNameCanonical(strtolower("test21"));
        $group->setCreatedAt(new \DateTime());
        $this->groupManager->persist($this->groupManager->convertInstance($group));
        $this->assertFalse($builder->setName("test21"));
        $this->assertTrue($builder->getLastError() === GroupBuilder::EXISTING_NAME);
        $this->assertTrue($builder->setName("test21", true));
        $this->groupManager->remove($this->groupManager->convertInstance($group));
        $this->assertFalse($builder->setName(" "));
        $this->assertTrue($builder->getLastError() === GroupBuilder::INVALID_NAME);
        $this->assertTrue($builder->setName(" ", true));
        
        $this->assertTrue($builder->setLocked(true));
        $this->assertTrue($builder->setLocked(false));
        $this->assertFalse($builder->setLocked("hello"));
        $this->assertTrue($builder->getLastError() === GroupBuilder::NOT_BOOLEAN);
        $this->assertTrue($builder->setLocked("world", true));
        
        $this->assertTrue($builder->setExpiresAt());
        $this->assertTrue($builder->setExpiresAt(new \DateTime()));
        $this->assertTrue($builder->setExpiresAt($dateAfterNow));
        $this->assertFalse($builder->setExpiresAt($dateBeforeNow));
        $this->assertTrue($builder->getLastError() === GroupBuilder::DATE_BEFORE_NOW);
        $this->assertTrue($builder->setExpiresAt($dateBeforeNow, true));
        
        $role = new Role();
        $role->setCreatedAt(new \DateTime());
        $role->setName("ROLE_TEST");
        
        $this->assertFalse($builder->addRole($role));
        $this->assertTrue($builder->getLastError() === GroupBuilder::UNEXISTING_ROLE);
        $this->assertTrue($builder->addRole($role, true));
        
        $this->assertTrue($builder->removeRole($role));
        $this->assertFalse($builder->removeRole($role));
        $this->assertTrue($builder->getLastError() === GroupBuilder::NOT_ROLE_OWNER);
        $this->assertTrue($builder->removeRole($role, true));
        
        $this->roleManager->persist($this->roleManager->convertInstance($role));
        $this->assertTrue($builder->addRole($role));
        $this->roleManager->remove($this->roleManager->convertInstance($role));
    }
}
