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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\SecurityBundle\Util\Provider;

use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Util\RoleBuilder
 */
class UserProvider
{
    /**
     * The current service container.
     *
     * This container is used to get
     * other services from the container.
     *
     * @var ContainerInterface
     */
    protected $container;

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
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
    public function findAllUsernames()
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('CscfaSecurityBundle:User');
        $result = $repository->getAllUsername();

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
    public function findAllEmail()
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('CscfaSecurityBundle:User');
        $result = $repository->getAllEmail();

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
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder|null
     */
    public function findOneByUsername($username)
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('CscfaSecurityBundle:User');
        $user = $repository->findOneByUsername($username);

        if ($user !== null) {
            $userManager = $this->container->get('core.manager.user_manager');
            $encoder = $this->container->get('security.encoder_factory');

            return new UserBuilder($userManager, $this, $encoder, $user);
        } else {
            return;
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
     * @return \Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder|null
     */
    public function findOneByEmail($email)
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('CscfaSecurityBundle:User');
        $user = $repository->findOneByEmail($email);

        if ($user !== null) {
            $userManager = $this->container->get('core.manager.user_manager');
            $encoder = $this->container->get('security.encoder_factory');

            return new UserBuilder($userManager, $this, $encoder, $user);
        } else {
            return;
        }
    }

    /**
     * Find all.
     *
     * This method allow to get all User
     * instances from the database.
     *
     * @return array
     */
    public function findAll()
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('CscfaSecurityBundle:User');

        return $repository->findAll();
    }
}
