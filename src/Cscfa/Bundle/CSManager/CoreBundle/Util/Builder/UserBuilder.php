<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Builder
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Builder;

use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;

/**
 * UserBuilder class.
 *
 * The UserBuilder class purpose feater to
 * manage a User entity with a StackUpdate
 * object into the database behind the
 * UserManager usage.
 *
 * @category Builder
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager
 */
class UserBuilder
{

    /**
     * An error type.
     *
     * This error represent that an
     * username is malformated, so
     * invalid. The format validation
     * of username is make by regex
     * test on [a-zA-Z0-9_].
     *
     * @var integer
     */
    const INVALID_USERNAME = 0;

    /**
     * An error type.
     *
     * This error represent that an
     * username is already existing
     * into the database.
     *
     * @var integer
     */
    const DUPLICATE_USERNAME = 1;

    /**
     * An error type.
     *
     * This error represent that an
     * email is malformated, so
     * invalid. The format validation
     * of email is make by regex test
     * on /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i
     *
     * @var integer
     */
    const INVALID_EMAIL = 2;

    /**
     * An error type.
     *
     * This error represent that an
     * email is already existing
     * into the database.
     *
     * @var integer
     */
    const DUPLICATE_EMAIL = 3;

    /**
     * The user instance.
     *
     * This is the encapsulated user instance
     * that the builder manage.
     *
     * @var User
     */
    protected $user;

    /**
     * The UserManager service.
     *
     * This allow to process specific
     * validations for the User instance
     * by usage into the getters and
     * the setters.
     *
     * @var UserManager
     */
    protected $manager;

    /**
     * The UserProvider service.
     *
     * This allow to get User instance
     * from the database and retreive
     * specific informations.
     *
     * @var UserProvider
     */
    protected $provider;

    /**
     * The security encoder factory.
     *
     * This allow to process the password
     * encodage into user password setters.
     *
     * @var EncoderFactoryInterface
     */
    protected $encoder;

    /**
     * The stack update entity.
     *
     * This allow to store an image
     * of the User previous state to
     * use in backup case.
     *
     * It can be null if the given User
     * is null.
     *
     * @var StackUpdate
     */
    protected $stackUpdate;

    /**
     * The last error.
     *
     * This represent the last error
     * registered by a method failure.
     *
     * @var integer
     */
    protected $lastError;

    /**
     * The default UserBuilder constructor.
     *
     * This constructor register a UserManager,
     * an UserProvider to get User informations
     * and an EncoderFactoryInterface to allow
     * validation management on User instance
     * setters.
     *
     * It take an optionnal User instance that
     * is used to create a StackUpdate instance.
     * If the User argument is null, a new
     * User instance is created and the StackUpdate
     * instance is null.
     *
     * @param UserManager             $manager  The UserManager service to process validations.
     * @param UserProvider            $provider The UserProvider service to get User instances and informations.
     * @param EncoderFactoryInterface $encoder  The encoder factory interface to process password encodage.
     * @param User                    $user     The User instance to encapsulate.
     *
     * @return void
     */
    public function __construct(UserManager $manager, UserProvider $provider, EncoderFactoryInterface $encoder, $user = null)
    {
        $this->manager = $manager;
        $this->provider = $provider;
        $this->encoder = $encoder;
        
        if ($user !== null && $user instanceof User) {
            $this->user = $user;
            $this->stackUpdate = new StackUpdate();
            $this->stackUpdate->setPreviousState(serialize($user));
            $this->stackUpdate->setDate(new \DateTime());
        } else {
            $this->user = new User();
            $this->stackUpdate = null;
        }
    }

    /**
     * Adding a role.
     *
     * This method allow to add a role for
     * the current User instance. By default,
     * it will verify that the Role instance
     * exist into the database and will
     * persist it if not.
     *
     * The default process can be skip by
     * passing true on second argument, this
     * will force the Role insertion into
     * the user instance without check.
     *
     * @param Role|null $role  The Role instance to insert.
     * @param boolean   $force The validation force state.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder
     */
    public function addRole(Role $role = null, $force = false)
    {
        if ($role === null) {
            return $this;
        }
        
        if ($this->manager->getRoleManager()->roleExists($role->getName()) || $force) {
            $this->user->addRole($role);
        } else {
            $roleBuilder = $this->manager->getRoleManager()->convertInstance($role);
            $this->manager->getRoleManager()->persist($roleBuilder);
            
            $this->user->addRole($role);
        }
        
        return $this;
    }

    /**
     * Setting the username.
     *
     * This method allow to set the username
     * of the current User instance. It
     * valid the username format for only
     * lower-case non accent characters, same
     * on upper-case, numbers and underscore.
     *
     * If the username is valid, this method
     * also register the username canonical
     * as the samo of username in lower-case.
     *
     * A second validation is run to valid
     * that no other user have the given
     * username.
     *
     * This validation can be skip by passing
     * true as second argument. This will force
     * the validation process.
     *
     * @param string  $username The new username.
     * @param boolean $force    The validation force state.
     *
     * @return boolean|\Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\UserBuilder
     */
    public function setUsername($username, $force = false)
    {
        if ($this->manager->isUsernameValid($username) || $force) {
            
            $canonical = strtolower($username);
            if (! in_array($canonical, $this->provider->getAllUsernames()) || $force || $this->user->getUsernameCanonical() === $canonical) {
                $this->user->setUsername($username);
                $this->user->setUsernameCanonical($canonical);
                return true;
            } else {
                $this->lastError = self::DUPLICATE_USERNAME;
                return false;
            }
        } else {
            $this->lastError = self::INVALID_USERNAME;
            return false;
        }
        
        return $this;
    }

    /**
     * Setting the email.
     *
     * This method allow to set the email
     * of the current User instance. It valid
     * the email format according to general
     * email regular expression.
     *
     * @param string $email
     * @param string $force
     * @return boolean
     */
    public function setEmail($email, $force = false)
    {
        if ($this->manager->isEmailValid($email) || $force) {
            $canonical = strtolower($email);
            if (! in_array($canonical, $this->provider->getAllEmail()) || $force || $this->user->getEmail()) {
                $this->user->setEmail($email);
                $this->user->setEmailCanonical($canonical);
                return true;
            } else {
                $this->lastError = self::DUPLICATE_EMAIL;
                return false;
            }
        } else {
            $this->lastError = self::INVALID_EMAIL;
            return false;
        }
    }
}
