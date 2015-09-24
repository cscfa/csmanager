<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;

/**
 * UserAddCommand class.
 *
 * The UserAddCommand class purpose feater to
 * generate a new user into the database.
 *
 * @category Controller
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.2
 * @link     http://cscfa.fr
 */
class UserAddCommand extends ContainerAwareCommand
{

    /**
     * The doctrine entity manager.
     *
     * This variable is used to manage
     * User instance storage into the
     * database.
     *
     * @var EntityManager
     */
    protected $doctrineManager;

    /**
     * The EncoderFactoryInterface.
     * 
     * This variable is used to process
     * the user password encodage.
     * 
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * The role provider service.
     * 
     * This service allow to
     * access to the database
     * behind the Roles tables.
     * 
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * The user manager service.
     * 
     * This allow to use
     * the UserBuilder
     * instances.
     * 
     * @var UserManager
     */
    protected $userManager;

    /**
     * UserAddCommand constructor.
     *
     * This constructor register a doctrine
     *.entity manager and an encoder factory. 
     *Also it call the parent constructor.
     *
     * @param EntityManager           $doctrineManager An entity manager to manage User instance into the database.
     * @param EncoderFactoryInterface $encoderFactory  A security encoder factory to encode user password.
     * @param RoleProvider            $roleProvider    The role provider service that allow to access to database.
     * @param UserManager             $userManager     The user manager service that allow to use UserBuilder
     */
    public function __construct(EntityManager $doctrineManager, EncoderFactoryInterface $encoderFactory, RoleProvider $roleProvider, UserManager $userManager)
    {
        $this->doctrineManager = $doctrineManager;
        $this->encoderFactory = $encoderFactory;
        $this->roleProvider = $roleProvider;
        $this->userManager = $userManager;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:add:user". It declare
     * four optional arguments to precise all of the user
     * attributes, and four options.
     * 
     * The defined argumants are username, email, password
     * and roles.
     * 
     * The defined options are enabled, salt, confirmation
     * token and expiration date.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:generate:user')
            ->setDescription('Create and register new user')
            ->addArgument('username', InputArgument::OPTIONAL, "What's the username?")
            ->addArgument('email', InputArgument::OPTIONAL, "What's the user mail?")
            ->addArgument('password', InputArgument::OPTIONAL, "What's the user password?")
            ->addOption('enabled', '-en', InputOption::VALUE_OPTIONAL, "If the user is enabled?")
            ->addOption('salt', '-sa', InputOption::VALUE_OPTIONAL, "What's the user salt?")
            ->addOption('confirmationToken', '-co', InputOption::VALUE_OPTIONAL, "What's the user confirmationToken?")
            ->addOption('expiresAt', '-ex', InputOption::VALUE_OPTIONAL, "What's the user account expiration date?")
            ->addArgument('roles', InputArgument::IS_ARRAY, "What's the user roles?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will create a new user into
     * the database by using the given arguments and options or
     * by using an interactive shell to catch inforations.
     * 
     * In order to proceed, this method will check the Role 
     * existance before inserting it into the user instance.
     * If it not exist, will exit.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @version Release: 1.2
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rolesNames = $this->roleProvider->findAllNames();
        if (empty($rolesNames)) {
            $rolesActive = false;
        } else {
            $rolesActive = true;
        }
        
        $multi = array(
            array(
                "var" => "username",
                "question" => "Username",
                "type" => CommandAskBuilder::TYPE_ASK,
                "extra" => array(
                    "empty" => false,
                    "default" => false
                )
            ),
            array(
                "var" => "email",
                "question" => "Email",
                "type" => CommandAskBuilder::TYPE_ASK,
                "extra" => array(
                    "empty" => false,
                    "default" => false
                )
            ),
            array(
                "var" => "password",
                "question" => "Password",
                "type" => CommandAskBuilder::TYPE_ASK,
                "option" => CommandAskBuilder::OPTION_ASK_HIDDEN_RESPONSE,
                "extra" => array(
                    "empty" => false,
                    "default" => false
                )
            ),
            array(
                "var" => "enabled",
                "question" => "enabled",
                "default" => true
            ),
            array(
                "var" => "salt",
                "question" => "Salt",
                "default" => base64_encode(utf8_encode(openssl_random_pseudo_bytes(10))),
                "type" => CommandAskBuilder::TYPE_ASK
            ),
            array(
                "var" => "confirmationToken",
                "question" => "Confirmation token",
                "default" => base64_encode(utf8_encode(openssl_random_pseudo_bytes(10))),
                "type" => CommandAskBuilder::TYPE_ASK
            ),
            array(
                "var" => "expiresAt",
                "question" => "Expiration date as Y-m-d H:i:s",
                "type" => CommandAskBuilder::TYPE_ASK,
                "default" => null,
                "extra" => array(
                    "transform" => function ($expire) {
                        $expire = \DateTime::createFromFormat("Y-m-d H:i:s", $expire);
                        if (! ($expire instanceof \DateTime) || $expire !== null) {
                            $expire = null;
                        }
                        return $expire;
                    }
                )
            ),
            array(
                "var" => "roles",
                "question" => "Roles",
                "type" => CommandAskBuilder::TYPE_ASK_SELECT,
                "limit" => $rolesNames,
                "option" => CommandAskBuilder::OPTION_ASK_MULTI_SELECT,
                "default" => array(),
                "extra" => array(
                    "active" => $rolesActive,
                    "unactive" => array()
                )
            )
        );
        
        $commandFacade = new CommandFacade($input, $output, $this);
        list ($username, $email, $password, $enabled, $salt, $confirmationToken, $expiresAt, $roles) = $commandFacade->getOrAskMulti($multi);
        
        $rolesSelected = array();
        foreach ($roles as $value) {
            $rolesSelected[] = $rolesNames[$value];
        }
        
        $confirmationArray = array(
            "username" => $username,
            "email" => $email,
            "password" => preg_replace("/./", "*", $password),
            "enabled" => $enabled,
            "salt" => $salt,
            "confirmation token" => $confirmationToken,
            "expiration date" => $expiresAt,
            "roles" => $rolesSelected
        );
        
        if ($commandFacade->getConfirmation($confirmationArray)) {
            
            $roles = array();
            foreach ($rolesSelected as $roleName) {
                $roles = $this->roleProvider->findOneByName($roleName)->getRole();
            }
            
            $userBuilder = $this->userManager->getNewInstance();
            
            $validating = array(
                "setUsername" => array(
                    $username,
                    "Username error",
                    array(
                        UserBuilder::DUPLICATE_USERNAME => "username exist",
                        UserBuilder::INVALID_USERNAME => "username format error"
                    )
                ),
                "setEmail" => array(
                    $email,
                    "Email error",
                    array(
                        UserBuilder::DUPLICATE_EMAIL => "email exist",
                        UserBuilder::INVALID_EMAIL => "email format error"
                    )
                ),
                "setEnabled" => array(
                    $enabled,
                    "Enabled error",
                    array(
                        UserBuilder::IS_NOT_BOOLEAN => "enabled is not a boolean type"
                    )
                ),
                "setSalt" => array(
                    $salt,
                    "Salt error",
                    array(
                        UserBuilder::IS_NOT_STRING => "salt is not a string type"
                    )
                ),
                "setPassword" => array(
                    $password,
                    "Password error",
                    array(
                        UserBuilder::EMPTY_PASSWORD => "password is empty",
                        UserBuilder::IS_NOT_STRING => "password is not a string"
                    )
                ),
                "setConfirmationToken" => array(
                    $confirmationToken,
                    "Confirmation token error",
                    array(
                        UserBuilder::IS_NOT_STRING => "Confirmation token is not a string type"
                    )
                ),
                "setExpiresAt" => array(
                    $expiresAt,
                    "Expiration error",
                    array(
                        UserBuilder::EXPIRATION_DATE_BEFORE_NOW => "Expiration date before now"
                    )
                ),
                "addRole" => array(
                    $roles,
                    "Role error",
                    array()
                )
            );
            
            $isValid = $commandFacade->applyAndValidate($userBuilder, $validating, "An error occured. Can't generate", "Generating succefull");
            
            if ($isValid) {
                $this->userManager->persist($userBuilder);
            }
        } else {
            return;
        }
    }
}
