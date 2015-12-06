<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Provider
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Util\Provider;

use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Util\RoleBuilder
 */
class RoleProvider
{

    /**
     * The current service container
     *
     * This container is used to get
     * other services from the container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RoleProvider constructor.
     * 
     * This constructor register the service container
     * to allow retreiving serices.
     *
     * @param ContainerInterface ContainerInterface The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
     * @see    Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder::getRole()
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder|NULL
     */
    public function findOneByName($name)
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Role");
        
        $role = $repository->findOneByName($name);
        
        if ($role !== null) {
            return new RoleBuilder($this->container->get("core.manager.role_manager"), $role);
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
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Role");
        return $repository->findAll();
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
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Role");
        return $repository->isExistingByName($name) === null ? false : true;
    }

    /**
     * Get all names.
     *
     * This method allow to get
     * all of the existings Roles
     * instance into the database.
     *
     * @return array
     */
    public function findAllNames()
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Role");
        return $repository->getAllNames();
    }
}
