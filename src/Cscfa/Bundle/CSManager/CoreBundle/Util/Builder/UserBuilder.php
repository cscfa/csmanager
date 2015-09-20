<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Builder
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Builder;

use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;

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
 * @version  Release: 1.1
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\User
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager
 */
class UserBuilder implements ErrorRegisteryInterface
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
     * An error type.
     * 
     * This error represent that a
     * role is unexisting into the
     * database. 
     * 
     * @var integer
     */
    const UNDEFINED_ROLE = 4;

    /**
     * An error type.
     * 
     * This error represent that
     * a role is not assigned to the
     * current user when a deletion
     * occured.
     * 
     * @var integer
     */
    const NOT_ASSIGNED_ROLE = 5;

    /**
     * An error type.
     * 
     * This error represent that
     * a given expiration date is
     * before the current date.
     * 
     * @var integer
     */
    const EXPIRATION_DATE_BEFORE_NOW = 6;

    /**
     * An error type.
     * 
     * This error represent that
     * a parameter asserted as
     * boolean is not a boolean.
     * 
     * @var integer
     */
    const IS_NOT_BOOLEAN = 7;

    /**
     * An error type.
     * 
     * This error represent that
     * a parameter asserted as
     * string password is an
     * empty string.
     * 
     * @var integer
     */
    const EMPTY_PASSWORD = 8;

    /**
     * An error type.
     * 
     * This error represent that
     * a parameter asserted as
     * string password is not a
     * typed string.
     * 
     * @var integer
     */
    const IS_NOT_STRING = 9;

    /**
     * An error type.
     * 
     * This error represent that
     * a parameter asserted as
     * a last user login date
     * is more avanced that the
     * current date.
     * 
     * @var integer
     */
    const LAST_LOGIN_AFTER_NOW = 10;

    /**
     * An error type.
     * 
     * This error represent that
     * a parameter asserted as
     * a date is time based after
     * the current date and time.
     * 
     * @var integer
     */
    const DATE_AFTER_NOW = 11;

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
        $this->lastError = self::NO_ERROR;
        
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
     * Remove a Role from the user.
     * 
     * This method allow to remove a role
     * from the current user. This will first
     * check if the Role exist and if the user
     * already has this Role. If one of this
     * check fail, the method will return false.
     * Also, if the checks success, this method
     * will return true.
     * 
     * It is possible to desable this first
     * check by passing true as second parameter.
     * In this case, the method will always return
     * true.
     * 
     * @param Role    $role  The role to remove
     * @param boolean $force The validation force state
     * 
     * @return boolean
     */
    public function removeRole(Role $role, $force = false)
    {
        if ($this->manager->getRoleManager()->roleExists($role->getName()) || $force) {
            if ($this->user->hasRole($role->getName()) || $force) {
                $this->user->removeRole($role->getName());
            } else {
                $this->lastError = self::NOT_ASSIGNED_ROLE;
                return false;
            }
        } else {
            $this->lastError = self::UNDEFINED_ROLE;
            return false;
        }
        
        return true;
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
     * @return boolean
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
    }

    /**
     * Setting the email.
     *
     * This method allow to set the email
     * of the current User instance. It valid
     * the email format according to general
     * email regular expression.
     *
     * @param string $email The new email
     * @param string $force The validation force state
     * 
     * @return boolean
     */
    public function setEmail($email, $force = false)
    {
        if ($this->manager->isEmailValid($email) || $force) {
            $canonical = strtolower($email);
            if (! in_array($canonical, $this->provider->getAllEmail()) || $force || $this->user->getEmailCanonical() === $canonical) {
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

    /**
     * Setting the credentials expiration date.
     * 
     * This method allow to specify a
     * credential expiration date. This
     * will check if the given expiration
     * date is after the current date. If
     * this validation fail, the method
     * will return false. Else if the date
     * is valid, the method will return true.
     * 
     * It's possible to force the validation
     * state by passing true as second parameter.
     * In this case the method will always
     * return true. The same effect will be
     * find when passing null as date.
     * 
     * @param \DateTime $date  The new date or null
     * @param string    $force The validation force state
     * 
     * @return boolean
     */
    public function setCredentialsExpireAt(\DateTime $date = null, $force = false)
    {
        $currentDate = new \DateTime();
        
        if ($currentDate <= $date || $date === null || $force) {
            $this->user->setCredentialsExpireAt($date);
            return true;
        } else {
            $this->lastError = self::EXPIRATION_DATE_BEFORE_NOW;
            return false;
        }
    }

    /**
     * Force the credential expiration state.
     * 
     * This method allow to force 
     * the expiration state of the 
     * credentials. If the needed state
     * is true, the expiration state
     * will be turn to true and the
     * expiration date will be set
     * to the current date. This method
     * will first valid that the given
     * state is a boolean. If not, it
     * return false. However, will return 
     * true.
     * 
     * If the needed state is false,
     * the expiration state will be turn
     * to false, and the expiration date
     * will be set to null.
     * 
     * It's possible to override the 
     * validation by passing true as
     * second parameter.
     * 
     * @param boolean $boolean The expiration state
     * @param boolean $force   The validation force state
     * 
     * @return void
     */
    public function setCredentialsExpired($boolean, $force = false)
    {
        if ($boolean === true || ((boolean) $boolean && $force)) {
            $currentDate = new \DateTime();
            $this->user->setCredentialsExpireAt($currentDate);
            $this->user->setCredentialsExpired(true);
            return true;
        } else if ($boolean === false || ((boolean) $boolean === false && $force)) {
            $this->user->setCredentialsExpireAt(null);
            $this->user->setCredentialsExpired(false);
            return true;
        } else {
            $this->lastError = self::IS_NOT_BOOLEAN;
            return false;
        }
    }

    /**
     * Set the enable state.
     * 
     * This method allow to set the
     * enabled state of an user account.
     * It will assert that the given
     * parameter is a boolean type. If
     * not, the method will return false,
     * however the method will return true.
     * 
     * It's possible to force the validation
     * by passing true as second parameter.
     * In this case, the first parameter
     * will be casted and the method will
     * return true.
     * 
     * @param boolean $boolean The enabled state
     * @param string  $force   The validation force state
     * 
     * @return boolean
     */
    public function setEnabled($boolean, $force = false)
    {
        if (! $force && $boolean !== true && $boolean !== false) {
            $this->lastError = self::IS_NOT_BOOLEAN;
            return false;
        }
        
        $this->user->setEnabled($boolean);
        return true;
    }

    /**
     * Set the expiration date.
     * 
     * This method allow to set the user
     * expiration date. It will assert
     * that the new date is past of the
     * current date. If not, the method
     * will return false. Else, the method
     * will return true.
     * 
     * It's possible to force the validation
     * by passing true as second parameter.
     * If it's the case, the method will
     * return true.
     * 
     * This method allow to passing null as
     * date. In this case, the date validation
     * is ignored.
     * 
     * @param \DateTime $date  The new expiration date
     * @param boolean   $force The validation force state
     * 
     * @return boolean
     */
    public function setExpiresAt(\DateTime $date = null, $force = false)
    {
        $currentDate = new \DateTime();
        
        if ($date !== null && $currentDate > $date && ! $force) {
            $this->lastError = self::EXPIRATION_DATE_BEFORE_NOW;
            return false;
        }
        
        $this->user->setExpiresAt($date);
        
        return true;
    }

    /**
     * Set the expired state.
     * 
     * This method allow to set the user
     * expired state. It will assert that
     * the given state is typed as boolean.
     * If not, the method will return false.
     * Else, it'll return true.
     * 
     * It's possible to override the validation
     * by passing true as second parameter. In
     * this case, the state will be cast as
     * boolean.
     * 
     * This method also set the expiration
     * date of the user to conserve the logic
     * state of the entity. If the new state
     * is false, the expiration date will
     * be set to null. If the state is true,
     * expiration date will be set to the
     * current date.
     * 
     * @param boolean $boolean The expired state
     * @param string  $force   The validation force state
     * 
     * @return boolean
     */
    public function setExpired($boolean, $force = false)
    {
        if (! $force && $boolean !== true && $boolean !== false) {
            $this->lastError = self::IS_NOT_BOOLEAN;
            return false;
        }
        
        if ($boolean) {
            $this->user->setExpiresAt(new \DateTime());
        } else {
            $this->user->setExpiresAt(null);
        }
        
        $this->user->setExpired((boolean) $boolean);
        
        return true;
    }

    /**
     * Set the password.
     * 
     * This method allow to set the user 
     * password. It will first assert that 
     * the  given password is not empty 
     * and is a string. No password format 
     * validation is applied by this method. 
     * It'll return false if a validation 
     * failed. However it'll return true. 
     * This method allow to passing null 
     * as password.
     * 
     * It's possible to desable the 
     * validation by passing true as 
     * second parameter. In this specific 
     * case, the method will always
     * return true.
     * 
     * This method set up the plain 
     * password field and encode the
     * password before apply to the
     * user as new password.
     * 
     * @param string  $password The plain password to set
     * @param boolean $force    The validation force state
     * 
     * @return boolean
     */
    public function setPassword($password = null, $force = false)
    {
        if (! $force && $password !== null && ($password == "" || empty($password))) {
            $this->lastError = self::EMPTY_PASSWORD;
            return false;
        } else if (! $force && $password !== null && ! is_string($password)) {
            $this->lastError = self::IS_NOT_STRING;
            return false;
        }
        
        $plainPassword = $password;
        
        if ($password !== null) {
            $password = $this->encoder->getEncoder($this->user)->encodePassword($password, $this->user->getSalt());
        }
        
        $this->user->setPassword($password);
        $this->user->setPlainPassword($plainPassword);
        
        return true;
    }

    /**
     * Set the user last login date.
     * 
     * This method allow to set the user 
     * last login date. It will first 
     * valid that the given date is 
     * before or equal the current date 
     * and time. If this validation fail, 
     * the method will return false. Else 
     * if the validation success, the 
     * method will return true.
     * 
     * It's possible to desable the 
     * validation stateby passing true 
     * as second parameter. In this 
     * case, the method will always 
     * return true.
     * 
     * @param \DateTime $time  The new user last login time
     * @param boolean   $force The validation force state
     * 
     * @return boolean
     */
    public function setLastLogin(\DateTime $time = null, $force = false)
    {
        $currentTime = new \DateTime();
        
        if ($time !== null && ($currentTime < $time && ! $force)) {
            $this->lastError = self::LAST_LOGIN_AFTER_NOW;
            return false;
        }
        
        $this->user->setLastLogin($time);
        
        return true;
    }

    /**
     * Set the user locked state.
     * 
     * This method allow to set the user 
     * locked state. It will assert that 
     * the first parameter is typed as 
     * boolean and valid this. If the 
     * validation failed, the method will 
     * return false. However, the method 
     * will return true.
     * 
     * It's possible to desable the 
     * validation by passing true as 
     * second parameter. In this case, 
     * the method will always return true.
     * 
     * @param boolean $boolean The locked state
     * @param string  $force   The validation force state
     * 
     * @return boolean
     */
    public function setLocked($boolean, $force = false)
    {
        if ($boolean !== false && $boolean !== true && ! $force) {
            $this->lastError = self::IS_NOT_BOOLEAN;
            return false;
        }
        
        $this->user->setLocked((boolean) $force);
        
        return true;
    }

    /**
     * Set the user confirmation token.
     * 
     * This method allow to set the user
     * confirmation token. It will first
     * validate that the given token is
     * typed as string. If not, the method
     * will return false, else return
     * true.
     * 
     * It's possible to desable the 
     * validation by passing true as 
     * second parameter. In this case, 
     * the method will always return true.
     * 
     * @param string  $confirmationToken The confirmation token string
     * @param boolean $force             The validation force state
     * 
     * @return boolean
     */
    public function setConfirmationToken($confirmationToken = null, $force = false)
    {
        if ($confirmationToken !== null && ! $force && ! is_string($confirmationToken)) {
            $this->lastError = self::IS_NOT_STRING;
            return false;
        }
        
        $this->user->setConfirmationToken($confirmationToken);
        
        return true;
    }

    /**
     * Set the password request date.
     * 
     * This method allow to set the user
     * password request date. It will first
     * validate that the new date is past
     * or equal the current date and time.
     * 
     * It's possible to desable the 
     * validation by passing true as 
     * second parameter. In this case, 
     * the method will always return true.
     * 
     * @param \DateTime $date  The request date
     * @param boolean   $force The validation force state
     * 
     * @return boolean
     */
    public function setPasswordRequestedAt(\DateTime $date = null, $force = false)
    {
        $currentTime = new \DateTime();
        
        if ($date !== null && $currentTime < $date && ! $force) {
            $this->lastError = self::DATE_AFTER_NOW;
            return false;
        }
        
        $this->user->setPasswordRequestedAt($date);
        
        return true;
    }

    /**
     * Set the user password salt.
     * 
     * This method allow to set the user
     * password salt. It will first validate
     * that the given salt is typed as string.
     * If not, the method will return false,
     * however, the method will return true.
     * 
     * It's possible to desable the 
     * validation by passing true as 
     * second parameter. In this case, 
     * the method will always return true.
     * 
     * Consider that the password need to be
     * set up after this method usage or
     * any login action will fail due to
     * encoding logical error du to unreferenced
     * salt hacking.
     * 
     * @param string  $salt  The new user salt
     * @param boolean $force The validation force state
     * 
     * @return boolean
     */
    public function setSalt($salt, $force = false)
    {
        if (! is_string($salt) && ! $force) {
            $this->lastError = self::IS_NOT_STRING;
            return false;
        }
        
        $this->user->setSalt($salt);
        return true;
    }

    /**
     * Get the username.
     * 
     * This method return the
     * user username as string.
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * Get the username canonical.
     * 
     * This method return the
     * user username canonical
     * as string.
     * 
     * @return string
     */
    public function getUsernameCanonical()
    {
        return $this->user->getUsernameCanonical();
    }

    /**
     * Get the email.
     * 
     * This method return the
     * user email as string.
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->user->getEmail();
    }

    /**
     * Get the email canonical.
     * 
     * This method return the
     * user email canonical
     * as string.
     * 
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->user->getEmailCanonical();
    }

    /**
     * Get the enable state.
     * 
     * This method return the
     * user enabled state as
     * boolean.
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->user->isEnabled();
    }

    /**
     * Get the password salt.
     * 
     * This method return the user
     * password salt as string.
     * 
     * @return string
     */
    public function getSalt()
    {
        return $this->user->getSalt();
    }

    /**
     * Get the password.
     * 
     * This method return the
     * user password as string.
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * Get the plain password.
     * 
     * This method return the
     * user plain text password
     * as string.
     * 
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->user->getPlainPassword();
    }

    /**
     * Get the last login.
     * 
     * This method return the
     * user last login date as
     * DateTime.
     * 
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->user->getLastLogin();
    }

    /**
     * Get the confirmation token.
     * 
     * This method return the
     * user confirmation token
     * as string.
     * 
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->user->getConfirmationToken();
    }

    /**
     * Get the password request date.
     * 
     * This method return the
     * user password request as
     * DateTime.
     * 
     * @return DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->user->getPasswordRequestedAt();
    }

    /**
     * Get the locked state.
     * 
     * This method return the
     * user locked state as 
     * boolean.
     * 
     * @return boolean
     */
    public function isLocked()
    {
        return $this->user->isLocked();
    }

    /**
     * Get the expiration state.
     * 
     * This method return the
     * user expiration state as 
     * boolean.
     * 
     * @return boolean
     */
    public function isExpired()
    {
        return $this->user->isExpired();
    }

    /**
     * Get the roles.
     * 
     * This method return the
     * user Roles as array of
     * Role.
     * 
     * @return multitype:Role
     */
    public function getRoles()
    {
        return $this->user->getRoles();
    }

    /**
     * Get the credential expired state.
     * 
     * This method return the
     * user credential expiration
     * state as boolean.
     * 
     * @return boolean
     */
    public function isCredentialsExpired()
    {
        return $this->user->isCredentialsExpired();
    }

    /**
     * Get the user StackUpdate.
     *
     * This method allow to get the current
     * user StackUpdate that represent the
     * state of the user before modification.
     *
     * For allow backup and modification trace,
     * this instance is stored into the database
     * and encapsule a serialized state of the
     * user.
     *
     * This instance is stored on construct
     * calling. If the given User is null at
     * this time, also the StackObject is
     * set to null.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\StackUpdate|null
     */
    public function getStackUpdate()
    {
        return $this->stackUpdate;
    }

    /**
     * Get the user.
     *
     * The UserBuilder object give abstraction
     * of the User interface but is not an
     * instance of. It encapsulate a User
     * instance and provide access to it
     * method with a logic validation.
     *
     * This method allow to get the
     * encapsulate User instance.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the last error.
     *
     * This method allow to get the
     * last error state. By default,
     * the last error is set to
     * RoleBuilder::NO_ERROR.
     *
     * @return number
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}
