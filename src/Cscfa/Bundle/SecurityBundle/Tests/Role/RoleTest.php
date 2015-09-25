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
namespace Cscfa\Bundle\SecurityBundle\Tests\Role;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\SecurityBundle\Entity\Role;
use Cscfa\Bundle\SecurityBundle\Entity\StackUpdate;
use Doctrine\ORM\EntityManager;

/**
 * RoleTest class.
 *
 * The RoleTest class provide method to test
 * the Role instance methods.
 *
 * @category Test
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Role
 */
class RoleTest extends WebTestCase
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
     * The testRole test.
     * 
     * This test is used to confirm
     * the Role class instance methods.
     * 
     * @return void
     */
    public function testRole()
    {
        $role = new Role();
        
        $this->assertTrue($role instanceof Role);
    }
}
