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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager;

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
     * The User manager.
     * 
     * This manager is used
     * to create user builder.
     * 
     * @var UserManager
     */
    protected $userManager;
    
    /**
     * The encoder factory.
     * 
     * This encoder is used
     * to create user builder.
     * 
     * @var EncoderFactoryInterface
     */
    protected $encoder;

    /**
     * The UserProvider constructor.
     *
     * This constructor register a doctrine manager
     * from what the User repository is retreived.
     *
     * @param EntityManager            $doctrineManager The entity manager to use to interact with database.
     * @param RoleManager              $roleManager     The role manager to be returned by the getRoleManager method
     * @param EncoderFactoryInterface  $encoder         The encoder factory service to hack user password
     * @param SecurityContextInterface $security        The security context to use to get current user.
     */
    public function __construct(EntityManager $doctrineManager, RoleManager $roleManager, EncoderFactoryInterface $encoder, SecurityContextInterface $security)
    {
        $this->repository = $doctrineManager->getRepository("CscfaCSManagerCoreBundle:User");
        $this->userManager = new UserManager($doctrineManager, $roleManager, $this, $encoder, $security);
        $this->encoder = $encoder;
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
    
    /**
     * Find one user by username or null.
     * 
     * This method allow to retreive
     * a user instance by it username.
     * 
     * It will be automaticaly inserted
     * into a UserBuilder instance.
     * 
     * @param string $username The username of the User to find
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder|NULL
     */
    public function findOneByUsername($username)
    {
        $user = $this->repository->findOneByUsername($username);
        
        if ($user !== null) {
            return new UserBuilder($this->userManager, $this, $this->encoder, $user);
        } else {
            return null;
        }
    }
    
    /**
     * Find one user by email or null.
     * 
     * This method allow to retreive
     * a user instance by it email.
     * 
     * It will be automaticaly inserted
     * into a UserBuilder instance.
     * 
     * @param string $email The email of the User to find
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder|NULL
     */
    public function findOneByEmail($email)
    {
        $user = $this->repository->findOneByEmail($email);
        
        if ($user !== null) {
            return new UserBuilder($this->userManager, $this, $this->encoder, $user);
        } else {
            return null;
        }
    }
}
