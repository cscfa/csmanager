<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
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
namespace Cscfa\Bundle\SecurityBundle\Tests\Group;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\SecurityBundle\Entity\Group;

/**
 * GroupProviderTest class.
 *
 * The GroupProviderTest class provide test to
 * valid GroupProvider methods process.
 *
 * @category Test
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\User
 */
class GroupProviderTest extends WebTestCase
{

    /**
     * The group provider service.
     *
     * This service is the main
     * tested class. It provide
     * abstraction to group access
     * into database.
     *
     * @var GroupProvider
     */
    protected $groupProvider;
    
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
        
        $this->groupProvider = static::$kernel->getContainer()->get('core.provider.group_provider');
        $this->doctrine = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * The testProvider test.
     *
     * This test is used to confirm
     * the GroupProvider service methods.
     *
     * @return void
     */
    public function testProvider()
    {
        $groupName = substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180);
        while (($def = substr(str_shuffle(str_repeat("_ABCDEFGHIJKLMNOPQRSTUWVXYZabcdefghijklmnopqrstuvwxyz", 180)), 0, 180)) === $groupName);
        
        $group = new Group();
        $group->setName($groupName);
        $group->setNameCanonical(strtolower($groupName));
        $group->setCreatedAt(new \DateTime());
        
        $this->doctrine->persist($group);
        $this->doctrine->flush();
        
        $allNames = $this->groupProvider->findAllNames();
        $this->assertTrue(in_array($groupName, $allNames));
        
        $getOne = $this->groupProvider->findOneByName($groupName);
        $getNull = $this->groupProvider->findOneByName($def);
        $this->assertTrue($getOne->getId() === $group->getId());
        $this->assertTrue($getNull === null);
        
        $this->assertTrue($this->groupProvider->isNameExist($groupName));
        $this->assertFalse($this->groupProvider->isNameExist($def));

        $this->doctrine->remove($group);
        $this->doctrine->flush();
    }
}
