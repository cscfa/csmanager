<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Manager
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Util\Manager;

use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UserManager class.
 *
 * The UserManager class purpose feater to
 * manage a User entity and it's logic. Also
 * the manager is capable to store and remove
 * an instance into the database.
 *
 * @category Manager
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserManager
{

    /**
     * The RoleManager service.
     *
     * This service allow the UserManager
     * to valid user role dependencies
     * methods.
     *
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * The UserProvider service.
     * 
     * This service allow the UserManager
     * and the UserBuilder to manage
     * the database access.
     * 
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * The EncoderFactory service.
     * 
     * This service allow the UserBuilder
     * to hach the user password.
     * 
     * @var EncoderFactoryInterface
     */
    protected $encoder;

    /**
     * The entity manager.
     *
     * This allow to register or remove the
     * current User instance into the database.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * The security context.
     *
     * This allow to register the current
     * application user into the User instance
     * as creator or updator.
     *
     * @var Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $security;
    
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
     * The UserManager constructor.
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
     * Get the RoleManager service.
     *
     * This method allow to get the RoleManager
     * service from the UserManager service to
     * manage Role validations.
     *
     * @return \Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager
     */
    public function getRoleManager()
    {
        return $this->container->get("core.manager.role_manager");
    }

    /**
     * Valid a username.
     *
     * This method valid a username with
     * a regex test and return true if
     * the regex match the username.
     *
     * @param string $username The username to test
     * 
     * @return boolean
     */
    public function isUsernameValid($username)
    {
        return preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/", $username) ? true : false;
    }

    /**
     * Valid an email.
     *
     * This method valid an email with
     * a regex test and return true if
     * the regex match the email.
     *
     * @param string $email The email to validate
     * 
     * @return boolean
     */
    public function isEmailValid($email)
    {
        return preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $email) ? true : false;
    }

    /**
     * Get a new instance of UserBuilder.
     *
     * This method create and return a new
     * instance of UserBuilder.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder
     */
    public function getNewInstance()
    {
        $userProvider = $this->container->get("core.provider.user_provider");
        $encoder = $this->container->get("security.encoder_factory");
        
        return new UserBuilder($this, $userProvider, $encoder);
    }

    /**
     * This method allow to store a UserBuilder
     * into the database. A UserBuilder is a
     * container that encapsulate a User instance
     * an a StackUpdate instance, so the two
     * objects will be persisted into their own
     * tables.
     *
     * It is possible to only store the StackUpdate
     * object, that allow to remove the User instance.
     * To do that, it is necessary to pass true as
     * second argument.
     *
     * Considering that use this method to store
     * only the StackUpdate object without remove
     * the User Object allow to create a User image
     * that only exist in StackUpdate table.
     * 
     * @param UserBuilder $userBuilder The user builder that contain the instance to persist
     * @param boolean     $onlyStack   The state of persisting. True to sotre only the StackUpdate object.
     * 
     * @return void
     */
    public function persist(UserBuilder $userBuilder, $onlyStack = false)
    {
        $manager = $this->container->get("doctrine.orm.entity_manager");
        
        if (! $onlyStack) {

            if ($userBuilder->getId()) {
                $userBuilder->getUser()->setUpdatedBy($this->getSecurityUser());
                $userBuilder->getUser()->setUpdatedAt(new \DateTime());
            } else {
                $userBuilder->getUser()->setCreatedBy($this->getSecurityUser());
                $userBuilder->getUser()->setCreatedAt(new \DateTime());
            }
            
            $manager->persist($userBuilder->getUser());
        }
        
        $stack = $userBuilder->getStackUpdate();
        if ($stack !== null) {
            $stack->setDate(new \DateTime());

            $securityUser = $this->getSecurityUser();
            if ($securityUser !== null) {
                $stack->setUpdatedBy($securityUser->getId());
            }
            $manager->persist($stack);
        }
        
        $manager->flush();
    }

    /**
     * Remove a User instance.
     *
     * This method allow to remove a User
     * instance from the database. It persist
     * the StackUpdate object before processing.
     * 
     * @param UserBuilder $user The user builder that contain instance to remove from the database.
     *
     * @throws Doctrine\ORM\OptimisticLockException 
     * @return void 
     */
    public function remove(UserBuilder $user)
    {
        $manager = $this->container->get("doctrine.orm.entity_manager");
        
        $this->persist($user, true);
        $manager->remove($user->getUser());
        $manager->flush();
    }

    /**
     * Convert instance of User.
     *
     * This method allow to convert a User
     * instance into UserBuilder instance.
     *
     * This allow to store a new StackUpdate
     * before or after modifications.
     *
     * Consider that this method can be called
     * everywhere and allow to store a new
     * StackUpdate with the calling moment User
     * image.
     *
     * @param User $user The user instance to convert.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder
     */
    public function convertInstance(User $user)
    {
        $userProvider = $this->container->get("core.provider.user_provider");
        $encoder = $this->container->get("security.encoder_factory");
        
        return new UserBuilder($this, $userProvider, $encoder, $user);
    }
    
    /**
     * Get security user.
     * 
     * This method allow to
     * get the current security
     * user.
     * 
     * @return User|NULL
     */
    protected function getSecurityUser()
    {
        $security = $this->container->get('security.context');
        
        if (method_exists($security, "getToken") && $security->getToken() !== null && method_exists($security->getToken(), "getUser") && $security->getToken()->getUser() !== null) {
            return $security->getToken()->getUser();
        } else {
            return null;
        }
    }
}
