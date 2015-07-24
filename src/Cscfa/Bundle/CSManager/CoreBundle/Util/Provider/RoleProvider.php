<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Provider
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Provider;

use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * RoleProvider class.
 *
 * The RoleProvider class purpose feater to
 * get Role instance from the database and
 * create RoleBuilder objects.
 *
 * The RoleBuilder objects allow security
 * issue to store Role images into the database
 * and allow a restoration for backup.
 *
 * @category Provider
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\RoleBuilder
 */
class RoleProvider
{

    /**
     * The Role repository.
     *
     * This repository purpose access
     * to doctrine service and method to
     * get Role instances.
     *
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * The RoleManager.
     *
     * This manager is needed for create
     * a RoleBuilder instance. It purpose
     * methods to validate a Role instance.
     *
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
     */
    protected $roleManager;

    /**
     * RoleProvider constructor.
     *
     * This method create an instance of RoleProvider
     * and store a doctrine repository fixed to Role
     * table and a SecurityContextInterface to create
     * a RoleManager and reference it when create a
     * RoleBuilder instances.
     *
     * @param EntityManager            $doctrineManager The doctrine manager instance to use to get Role instance from the database.
     * @param SecurityContextInterface $security        The security context to use to retreive the current user.
     */
    public function __construct(EntityManager $doctrineManager, SecurityContextInterface $security)
    {
        $this->repository = $doctrineManager->getRepository("CscfaCSManagerCoreBundle:Role");
        $this->roleManager = new RoleManager($doctrineManager, $this, $security);
    }

    /**
     * Find one Role by name or return null.
     *
     * This method allow to get a Role instance
     * identifyed by it name. If the database
     * can't return anything, this method will
     * return null.
     *
     * The Role instance will be encapsulated
     * into a RoleBuilder to use with RoleManager.
     * If this feature is unused, consider use
     * the getRole of RoleBuilder to retreive
     * the base instance.
     *
     * @param string $name The name of the Role to find.
     *            
     * @see    Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder::getRole()
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder|NULL
     */
    public function findOneByName($name)
    {
        $role = $this->repository->findOneByName($name);
        
        if ($role !== null) {
            return new RoleBuilder($this->roleManager, $role);
        } else {
            return null;
        }
    }

    /**
     * Find all Role.
     *
     * This method allow to get all
     * Roles instances from the database.
     *
     * Roles instances will be served into
     * an array of Role instances.
     *
     * @return Role[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Check if a Role exist.
     *
     * This method check if a Role exist in
     * the database by identifying a name.
     *
     * It will return true if exist, false
     * otherwise.
     *
     * @param string $name The name of the Role to check if exist.
     *            
     * @return boolean
     */
    public function isExistingByName($name)
    {
        return $this->repository->isExistingByName($name) === null ? false : true;
    }
}