<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User class.
 *
 * The User class is the main User entity for database
 * persistance. It precise logical storage informations.
 *
 * This entity is stored into the csmanager_core_user table
 * of the database.
 *
 * Featuring, this entity extend UserInterface to be used
 * as User into a security context.
 *
 * The repository of this entity is located into
 * the Entity\Repository folder of the core bundle.
 *
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\SecurityBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="csmanager_core_user",
 *      indexes={@ORM\Index(name="cs_manager_user_indx", columns={"user_username_canonical", "user_email_canonical"})}
 *      )
 */
class User extends StackableObject implements AdvancedUserInterface, \Serializable
{
    /**
     * The id field
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into user_id field into GUID
     * format, is unique and can't be null.
     *
     * @ORM\Column(
     *      type="guid", name="user_id", unique=true, nullable=false, options={"comment":"user identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * The username field.
     * 
     * The username parameter allow an
     * User to be named.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", length=255, name="user_username", options={"comment":"user name"}
     * )
     */
    protected $username;

    /**
     * The user cannonical name.
     * 
     * The cannonical name allow database
     * to prevent duplicate name by characters
     * case change.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", length=255, name="user_username_canonical", unique=true, options={"comment":"user canonical name"}
     * )
     */
    protected $usernameCanonical;

    /**
     * The email field.
     * 
     * This field store an user email.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", length=255, name="user_email", options={"comment":"user email"}
     * )
     */
    protected $email;

    /**
     * The email canonical field.
     * 
     * This field allow to prevent duplicate
     * email by characters case change.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", length=255, name="user_email_canonical", unique=true, options={"comment":"user canonical email"}
     * )
     */
    protected $emailCanonical;

    /**
     * The enabled field.
     * 
     * This field allow to enable or
     * desable an user account.
     * 
     * @var boolean
     * 
     * @ORM\Column(
     *      type="boolean", name="user_enabled", options={"comment":"user enable state"}
     * )
     */
    protected $enabled;

    /**
     * The salt field.
     * 
     * This field is used to create
     * the password of the user.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", name="user_salt", options={"comment":"user hashing salt"}
     * )
     */
    protected $salt;

    /**
     * The password field.
     * 
     * This field allow to store
     * the user password.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", name="user_password", options={"comment":"Encrypted password"}
     * )
     */
    protected $password;

    /**
     * The plain password.
     * 
     * Plain password. Used for model validation. 
     * Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * The last login.
     * 
     * This field store the last login
     * date of the user.
     * 
     * @var \DateTime
     * 
     * @ORM\Column(
     *      type="datetime", name="user_lastLogin", nullable=true, options={"comment":"Last user login"}
     * )
     */
    protected $lastLogin;

    /**
     * The confirmation token.
     * 
     * This field store the user
     * confirmation token.
     * 
     * @var string
     * 
     * @ORM\Column(
     *      type="string", name="user_confirmation_token", nullable=true, options={"comment":"Random string sent to the user email address in order to verify it"}
     * )
     */
    protected $confirmationToken;

    /**
     * The password request date.
     * 
     * This field allow to store the user
     * password request date.
     * 
     * @var \DateTime
     * 
     * @ORM\Column(
     *      type="datetime", name="user_password_requested_at", nullable=true, options={"comment":"User requesting password date"}
     * )
     */
    protected $passwordRequestedAt;

    /**
     * The groups.
     * 
     * This field store the user groups.
     *
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="tk_csmanager_user_join_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="group_id")}
     *      )
     */
    protected $groups;

    /**
     * The user locked state.
     * 
     * This field allow to store
     * the locked state of the user.
     * 
     * @var boolean
     * 
     * @ORM\Column(
     *      type="boolean", name="user_locked", options={"comment":"user locked state"}
     * )
     */
    protected $locked;

    /**
     * The user expiration state.
     * 
     * This field allow to store if the user
     * account has expired.
     * 
     * @var boolean
     * 
     * @ORM\Column(
     *      type="boolean", name="user_expired", options={"comment":"user expired state"}
     * )
     */
    protected $expired;

    /**
     * The user account expiration date.
     * 
     * This field allow to store the user
     * expiration date.
     * 
     * @var \DateTime
     * 
     * @ORM\Column(
     *      type="datetime", name="user_expires_at", nullable=true, options={"comment":"User expiration date"}
     * )
     */
    protected $expiresAt;

    /**
     * The user roles.
     * 
     * This field allow to store the user
     * roles.
     * 
     * @var Collection
     * 
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="tk_csmanager_user_join_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="role_id")}
     *      )
     */
    protected $roles;

    /**
     * The user credention expiration state.
     * 
     * This field allow to store the user 
     * credention expiration state.
     * 
     * @var Boolean
     * 
     * @ORM\Column(
     *      type="boolean", name="user_credentials_expired", options={"comment":"user credential expired state"}
     * )
     */
    protected $credentialsExpired;

    /**
     * The user credention expiration date.
     * 
     * This field allow to store the user 
     * credention expiration date.
     * 
     * @var \DateTime
     * 
     * @ORM\Column(
     *      type="datetime", name="user_credentials_expires_at", nullable=true, options={"comment":"User credential expiration date"}
     * )
     */
    protected $credentialsExpireAt;

    /**
     * The User constructor.
     * 
     * This constructor set to default
     * values the User variables.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->roles = new ArrayCollection();
        $this->credentialsExpired = false;
    }

    /**
     * Add a role.
     * 
     * This method allow to add a role
     * to the current user.
     * 
     * @param Role $role The Role to add.
     * 
     * @throws \Exception
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function addRole(Role $role)
    {
        if (! $role instanceof Role) {
            throw new \Exception("addRole takes a Role object as the parameter");
        }
        
        if (! $this->hasRole($role->getName())) {
            $this->roles->add($role);
        }
        
        return $this;
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->password,
                $this->salt,
                $this->usernameCanonical,
                $this->username,
                $this->expired,
                $this->locked,
                $this->credentialsExpired,
                $this->enabled,
                $this->id
            )
        );
    }

    /**
     * Unserializes the user.
     * 
     * This method allow to unserialize
     * an user instance.
     *
     * @param string $serialized The serialized instance
     * 
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));
        
        list ($this->password, $this->salt, $this->usernameCanonical, $this->username, $this->expired, $this->locked, $this->credentialsExpired, $this->enabled, $this->id) = $data;
    }

    /**
     * Remove credential.
     * 
     * Removes sensitive credential data 
     * from the user instance.
     * 
     * @return void
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Get id.
     * 
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Username.
     * 
     * Return the user username.
     * 
     * @see    \Symfony\Component\Security\Core\User\UserInterface::getUsername()
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get user canonical username.
     * 
     * Return the user canonical username.
     * 
     * @return string
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * Get the user salt.
     * 
     * Return the user password salt.
     * 
     * @see    \Symfony\Component\Security\Core\User\UserInterface::getSalt()
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get email.
     * 
     * Return the user email.
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get canonical email.
     * 
     * Return the user canonical email.
     * 
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * Get password.
     * 
     * Gets the encrypted password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get password.
     * 
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Get last login.
     * 
     * Gets the last login time.
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Get confirmation token.
     * 
     * Return the user confirmation token.
     * 
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Get roles.
     * 
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = array();
        
        foreach ($this->roles as $role) {
            $roles[] = $role->getName();
        }
        
        return array_unique($roles);
    }

    /**
     * Check if user has a role.
     * 
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role The needed role.
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles(), true);
    }

    /**
     * Is account expired.
     * 
     * Check if the user account has expired.
     * 
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        if (true === $this->expired) {
            return false;
        }
        
        if (null !== $this->expiresAt && $this->expiresAt->getTimestamp() < time()) {
            return false;
        }
        
        return true;
    }

    /**
     * Is account non locked.
     * 
     * Check if the user is locked. Return
     * true if not.
     * 
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return ! $this->locked;
    }

    /**
     * Is credential non expired.
     * 
     * Check if the user credentials are expired. 
     * Return true if not.
     * 
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        if (true === $this->credentialsExpired) {
            return false;
        }
        
        if (null !== $this->credentialsExpireAt && $this->credentialsExpireAt->getTimestamp() < time()) {
            return false;
        }
        
        return true;
    }

    /**
     * Is credential expired.
     * 
     * Check if the user credentials are expired. 
     * Return true if expired.
     * 
     * @return boolean
     */
    public function isCredentialsExpired()
    {
        return ! $this->isCredentialsNonExpired();
    }

    /**
     * Is enabled
     * 
     * Check if the user is enable.
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Is expired
     * 
     * Check if the user is expired.
     * 
     * @return boolean
     */
    public function isExpired()
    {
        return ! $this->isAccountNonExpired();
    }

    /**
     * Is locked
     * 
     * Check if the user is locked.
     * 
     * @return boolean
     */
    public function isLocked()
    {
        return ! $this->isAccountNonLocked();
    }

    /**
     * Is user.
     * 
     * Check if the current user is the given user.
     * 
     * @param UserInterface $user the user to check.
     * 
     * @return boolean
     */
    public function isUser(UserInterface $user = null)
    {
        return null !== $user && $this->getId() === $user->getId();
    }

    /**
     * Remove Role.
     * 
     * Remove a Role from the current user.
     * 
     * @param string $role The Role to remove.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function removeRole($role)
    {
        foreach ($this->roles as $key => $roleElm) {
            if ($roleElm->getName() == $role) {
                unset($this->roles[$key]);
            }
        }
        
        return $this;
    }

    /**
     * Set username.
     * 
     * Set the current user username.
     * 
     * @param string $username The new username.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        
        return $this;
    }

    /**
     * Set username canonical.
     * 
     * Set the current user username canonical.
     * 
     * @param string $usernameCanonical The new username canonical.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
        
        return $this;
    }

    /**
     * Set credential expire at.
     * 
     * Set the user credential expiration date.
     * 
     * @param \DateTime $date The new expiration date.
     *
     * @return User
     */
    public function setCredentialsExpireAt(\DateTime $date = null)
    {
        $this->credentialsExpireAt = $date;
        
        return $this;
    }

    /**
     * Set credential expired.
     * 
     * Set the user credential expiration state.
     * 
     * @param boolean $boolean The new expiration state.
     *
     * @return User
     */
    public function setCredentialsExpired($boolean)
    {
        $this->credentialsExpired = $boolean;
        
        return $this;
    }

    /**
     * Set email.
     * 
     * Set the user email.
     * 
     * @param string $email The new email.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }

    /**
     * Set email canonical.
     *
     * Set the user email canonical.
     *
     * @param string $emailCanonical The new email canonical.
     *
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
        
        return $this;
    }

    /**
     * Set enabled.
     * 
     * Set the user enable state.
     * 
     * @param boolean $boolean The enale state.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (boolean) $boolean;
        
        return $this;
    }

    /**
     * Set expired.
     * 
     * Sets this user to expired.
     *
     * @param Boolean $boolean The expired state.
     *
     * @return User
     */
    public function setExpired($boolean)
    {
        $this->expired = (boolean) $boolean;
        
        return $this;
    }

    /**
     * Set expire at.
     * 
     * Set the user expiration date.
     * 
     * @param \DateTime $date The expiration date.
     *
     * @return User
     */
    public function setExpiresAt($date)
    {
        $this->expiresAt = $date;
        
        return $this;
    }

    /**
     * Set password.
     * 
     * Set the user password.
     * 
     * @param string $password the new password.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        
        return $this;
    }

    /**
     * Set plain password.
     * 
     * Set the user plain password.
     * 
     * @param string $password The user password.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        
        return $this;
    }

    /**
     * Set last login.
     * 
     * Set the user last login date.
     * 
     * @param \DateTime $time The last login date.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setLastLogin($time)
    {
        $this->lastLogin = $time;
        
        return $this;
    }

    /**
     * Set locked.
     * 
     * Set the user locked state.
     * 
     * @param boolean $boolean The locked state.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setLocked($boolean)
    {
        $this->locked = $boolean;
        
        return $this;
    }

    /**
     * Set confirmation token.
     * 
     * Set the user confirmation token.
     * 
     * @param string $confirmationToken The confirmation token.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        
        return $this;
    }

    /**
     * Set password request.
     * 
     * Set the user password request date.
     * 
     * @param \DateTime $date The request date.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;
        
        return $this;
    }

    /**
     * Get passord request date.
     * 
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * Is password request non expired.
     * 
     * Check if the request password  date is
     * expired for a given timestamp.
     * 
     * @param integer $ttl The expiration timestamp.
     * 
     * @return boolean
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime && $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * Set roles.
     * 
     * Set the user roles.
     * 
     * @param array $roles The roles as array.
     * 
     * @return \Cscfa\Bundle\SecurityBundle\Entity\User
     */
    public function setRoles(array $roles)
    {
        $this->roles = new ArrayCollection();
        
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        
        return $this;
    }

    /**
     * To String.
     * 
     * Parse the current User to string.
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }

    /**
     * Set salt.
     * 
     * Set the user password salt.
     * 
     * @param string $salt The salt.
     * 
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
}
