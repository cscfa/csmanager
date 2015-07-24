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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate;
use Doctrine\ORM\EntityManager;

/**
 * RoleTest class.
 *
 * The RoleTest class provide method to test
 * the Role instance methods.
 *
 * @category Test
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
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
        
        $this->assertInstanceOf(Role::class, $role);
    }
}
