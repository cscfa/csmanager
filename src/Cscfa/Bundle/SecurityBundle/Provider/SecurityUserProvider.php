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
namespace Cscfa\Bundle\SecurityBundle\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;

/**
 * SecurityUserProvider class.
 * 
 * This class is the main
 * security bundle provider
 * for the User instance
 * into a security context.
 * 
 * @category Provider
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class SecurityUserProvider implements UserProviderInterface
{

    protected $userProvider;

    /**
     * Default constructor.
     * 
     * This constructor register
     * the user provider instance
     * service to retreive the
     * user instance from the
     * database.
     * 
     * @param UserProvider $providerService The user provider service
     */
    public function __construct(UserProvider $providerService)
    {
        $this->userProvider = $providerService;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class The class name to test
     *
     * @see    \Symfony\Component\Security\Core\User\UserProviderInterface::supportsClass()
     * @return boolean
     */
    public function supportsClass($class)
    {
        $userClass = "Cscfa\Bundle\SecurityBundle\Entity\User";
        $userBuilderClass = "Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder";
        
        return ($class === $userClass || $class === $userBuilderClass);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user The user instance to reload
     *
     * @throws UnsupportedUserException if the account is not supported
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        $userClass = get_class($user);
        if (! $this->supportsClass($userClass)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $userClass), 500);
        }
        
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @see    UsernameNotFoundException
     * @throws UsernameNotFoundException if the user is not found
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userProvider->findOneByUsername($username);
        
        if ($user === null) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username), 404);
        }
        
        if ($user instanceof User) {
            return $user;
        } else if ($user instanceof UserBuilder) {
            return $user->getUser();
        }
    }
}
