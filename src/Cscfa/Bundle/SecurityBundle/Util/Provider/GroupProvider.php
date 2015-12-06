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

use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\SecurityBundle\Entity\Repository\GroupRepository;
use Cscfa\Bundle\SecurityBundle\Util\Builder\GroupBuilder;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * GroupProvider class.
 *
 * The GroupProvider class purpose feater to
 * get Group instance from the database and
 * create GroupBuilder objects.
 *
 * The GroupProvider objects allow security
 * issue to store Group images into the database
 * and allow a restoration for backup.
 *
 * @category Provider
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Util\RoleBuilder
 */
class GroupProvider
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
     * The GroupProvider constructor.
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
     * Get all names.
     * 
     * This method allow to get all
     * of the existing Group names.
     * 
     * @return array
     */
    public function findAllNames()
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Group");
        return $repository->getAllName();
    }

    /**
     * Check if a named group exist.
     * 
     * This method allow to check if
     * a group exist by checking it
     * name existance.
     * 
     * @param string $name the name of the group to search for
     * 
     * @return boolean
     */
    public function isNameExist($name)
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Group");
        return $repository->isExistingByName($name) !== null ? true : false;
    }

    /**
     * Get one by name.
     * 
     * This method allow to get
     * a group by it name or null.
     * 
     * @param string $name the group name
     * 
     * @return GroupBuilder|null
     */
    public function findOneByName($name)
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Group");
        $manager = new GroupManager($this->container);
        
        $group = $repository->findOneByName($name);
        
        if ($group !== null) {
            return new GroupBuilder($manager, $group);
        } else {
            return null;
        }
    }
    
    /**
     * Find all.
     * 
     * This method allow to
     * get all the existings
     * Groups instance from
     * the database.
     * 
     * @return array
     */
    public function findAll()
    {
        $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("CscfaSecurityBundle:Group");
        return $repository->findAll();
    }
}
