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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Provider;

use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\GroupManager;
use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Repository\GroupRepository;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\GroupBuilder;

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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\RoleBuilder
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
     * @param EntityManager $doctrineManager The entity manager to use to interact with database.
     */
    public function __construct(EntityManager $doctrineManager)
    {
        $this->repository = $doctrineManager->getRepository("CscfaCSManagerCoreBundle:Group");
        $this->manager = new GroupManager($this, $doctrineManager);
    }

    /**
     * Get all names.
     * 
     * This method allow to get all
     * of the existing Group names.
     * 
     * @return array
     */
    public function getAllNames()
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
    public function getOneByName($name)
    {
        $group = $this->repository->getOneByName($name);
        
        if ($group !== null) {
            return new GroupBuilder($this->manager, $group);
        } else {
            return null;
        }
    }
}
