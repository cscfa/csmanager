<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
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
     * The Group repository.
     * 
     * This allow the provider to access
     * quikly to the group table into
     * the database.
     * 
     * @var GroupRepository
     */
    protected $repository;

    /**
     * A group manager.
     * 
     * This manager allow
     * to create new Group
     * builder instance.
     * 
     * @var GroupManager
     */
    protected $manager;

    /**
     * The GroupProvider constructor.
     *
     * This constructor register a doctrine manager
     * from what the Group repository is retreived.
     *
     * @param EntityManager            $doctrineManager The entity manager to use to interact with database.
     * @param RoleManager              $roleManager     The role manager service to create a group manager.
     * @param SecurityContextInterface $security        The security context service
     */
    public function __construct(EntityManager $doctrineManager, RoleManager $roleManager, SecurityContextInterface $security)
    {
        $this->repository = $doctrineManager->getRepository("CscfaSecurityBundle:Group");
        $this->manager = new GroupManager($this, $doctrineManager, $roleManager, $security);
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
        return $this->repository->getAllName();
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
        return $this->repository->isExistingByName($name) !== null ? true : false;
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
        $group = $this->repository->findOneByName($name);
        
        if ($group !== null) {
            return new GroupBuilder($this->manager, $group);
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
        return $this->repository->findAll();
    }
}
