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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\GroupBuilder;
use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;

/**
 * GroupManagerTest class.
 *
 * The GroupManagerTest class provide test to
 * valid GroupManager methods process.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 */
class GroupManagerTest extends WebTestCase
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
        
        $this->groupManager = static::$kernel->getContainer()->get('core.manager.group_manager');
        $this->doctrine = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * The testManager test.
     *
     * This test is used to confirm
     * the GroupManager service methods.
     *
     * @return void
     */
    public function testManager()
    {
        $groupName = substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180);
        $invalidName = " ";
        
        $group = new Group();
        $group->setName($groupName);
        $group->setNameCanonical(strtolower($groupName));
        $group->setCreatedAt(new \DateTime());
        
        $converted = $this->groupManager->convertInstance($group);
        $this->assertTrue($converted instanceof GroupBuilder);

        $this->groupManager->persist($converted);
        $spyGroup = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\CoreBundle\Entity\Group")->findOneByName($groupName);
        $this->assertTrue($spyGroup->getId() === $group->getId());
        
        $this->assertTrue($this->groupManager->getRoleManager() instanceof  RoleManager);
        $this->assertTrue($this->groupManager->getNewInstance() instanceof  GroupBuilder);
        
        $this->assertTrue($this->groupManager->nameExist($groupName));
        $this->assertFalse($this->groupManager->nameExist($invalidName));
        

        $this->assertTrue($this->groupManager->nameIsValid($groupName));
        $this->assertFalse($this->groupManager->nameIsValid($invalidName));
        
        $this->groupManager->remove($converted);
        $spyGroup = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\CoreBundle\Entity\Group")->findOneByName($groupName);
        $this->assertTrue($spyGroup === null);
    }
}
