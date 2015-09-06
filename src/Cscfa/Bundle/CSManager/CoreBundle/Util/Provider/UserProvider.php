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

use Doctrine\ORM\EntityManager;

/**
 * UserProvider class.
 *
 * The UserProvider class purpose feater to
 * get User instance from the database and
 * create UserBuilder objects.
 *
 * The UserProvider objects allow security
 * issue to store User images into the database
 * and allow a restoration for backup.
 *
 * @category Provider
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\RoleBuilder
 */
class UserProvider
{

    /**
     * The User repository.
     *
     * This repository purpose access
     * to doctrine service and method to
     * get User instances.
     *
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * The UserProvider constructor.
     *
     * This constructor register a doctrine manager
     * from what the User repository is retreived.
     *
     * @param EntityManager $doctrineManager The doctrine manager to get User repository.
     */
    public function __construct(EntityManager $doctrineManager)
    {
        $this->repository = $doctrineManager->getRepository("CscfaCSManagerCoreBundle:User");
    }

    /**
     * Get all usernames.
     *
     * This method return all of the
     * existing and distincts canonical
     * usernames from the database into
     * an array of string.
     *
     * @return string[]
     */
    public function getAllUsernames()
    {
        $result = $this->repository->getAllUsername();
        
        if ($result === null) {
            return array();
        } else {
            return $result;
        }
    }

    /**
     * Get all email.
     *
     * This method return all of the
     * existing and distincts canonical
     * email from the database into
     * an array of string.
     *
     * @return string[]
     */
    public function getAllEmail()
    {
        $result = $this->repository->getAllEmail();
        
        if ($result === null) {
            return array();
        } else {
            return $result;
        }
    }
}
