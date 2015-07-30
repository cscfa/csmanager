<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Manager
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Util\Manager;

/**
 * UserManager class.
 *
 * The UserManager class purpose feater to
 * manage a User entity and it's logic. Also
 * the manager is capable to store and remove
 * an instance into the database.
 *
 * @category Manager
 * @package  CscfaCSManagerCoreBundle
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
     * The UserManager constructor.
     *
     * This constructor register a RoleManager
     * to provide access to Role validation into
     * some class that depend of User instance.
     *
     * @param RoleManager $roleManager The RoleManager service to manage Role validations.
     */
    public function __construct(RoleManager $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    /**
     * Get the RoleManager service.
     *
     * This method allow to get the RoleManager
     * service from the UserManager service to
     * manage Role validations.
     *
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
     */
    public function getRoleManager()
    {
        return $this->roleManager;
    }

    /**
     * Valid a username.
     *
     * This method valid a username with
     * a regex test and return true if
     * the regex match the username.
     *
     * @param string $username
     * @return boolean
     */
    public function isUsernameValid($username)
    {
        return preg_match("/^[a-zA-Z0-9_]+$/", $username) ? true : false;
    }

    /**
     * Valid an email.
     *
     * This method valid an email with
     * a regex test and return true if
     * the regex match the email.
     *
     * @param string $email
     * @return boolean
     */
    public function isEmailValid($email)
    {
        return preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $email) ? true : false;
    }
}
