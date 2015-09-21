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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;

/**
 * UserUpdateCommand class.
 *
 * The UserUpdateCommand class purpose feater to
 * update a User that is registered into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserUpdateCommand extends ContainerAwareCommand
{

    /**
     * The user provider service.
     * 
     * This allow to manage
     * the database for the
     * User instance.
     * 
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * The user manager service.
     * 
     * This allow to manage
     * the User instance logic.
     * 
     * @var UserManager
     */
    protected $userManager;

    /**
     * The role provider service.
     * 
     * This allow to manage
     * the database for the
     * Role instances.
     * 
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * UserUpdateCommand constructor.
     * 
     * The default UserUpdateCommand constructor.
     * 
     * @param UserProvider $userProvider The user provider service that allow to manage the database
     * @param UserManager  $userManager  The user manager service that allow to manage the user logic
     * @param RoleProvider $roleProvider The role provider service that allow to manage the database
     */
    public function __construct(UserProvider $userProvider, UserManager $userManager, RoleProvider $roleProvider)
    {
        $this->userProvider = $userProvider;
        $this->userManager = $userManager;
        $this->roleProvider = $roleProvider;
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:update:user". It declare
     * only one optional argument username that represent the 
     * user username to update.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:update:user')
            ->setDescription('Update an user')
            ->addArgument('username', InputArgument::OPTIONAL, "What's the user username to update?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will update an existing user 
     * into the database.All of the needed informations will be ask
     * behind the shell as an interactive element.
     *
     * This command will check some behavior to grant the user
     * instance logic.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see     \Symfony\Component\Console\Command\Command::execute()
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        list ($username) = $commandFacade->getOrAskMulti(
            array(
                array(
                    "var" => "username",
                    "question" => "User username",
                    "type" => CommandAskBuilder::TYPE_ASK,
                    "option" => CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
                    "completion" => $this->userProvider->findAllUsernames(),
                    "extra" => array(
                        "empty" => false,
                        "default" => false
                    )
                )
            )
        );
        
        $user = $this->userProvider->findOneByUsername($username);
        
        if (! $user) {
            $cf = new CommandColorFacade($output);
            $cf->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED, null);
            $cf->clear();
            $cf->addText("\n");
            $cf->addText("\nUnexisting group " . $username . ".\n", "error");
            $cf->addText("\n");
            $cf->write();
            
            return;
        }
        
        $commandFacade->askATWIL(
            $user, 
            "finish", 
            "What to update", 
            array(
                "username" => array(
                    "ask" => array(
                        "question" => "Name : ",
                        "type" => CommandAskBuilder::TYPE_ASK
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "email" => array(
                    "ask" => array(
                        "question" => "Email : ",
                        "type" => CommandAskBuilder::TYPE_ASK
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "password" => array(
                    "ask" => array(
                        "question" => "Password : ",
                        "type" => CommandAskBuilder::TYPE_ASK,
                        "option" => CommandAskBuilder::OPTION_ASK_HIDDEN_RESPONSE
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "salt" => array(
                    "ask" => array(
                        "question" => "Salt : ",
                        "type" => CommandAskBuilder::TYPE_ASK
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "confirmationToken" => array(
                    "ask" => array(
                        "question" => "Confirmation token : ",
                        "type" => CommandAskBuilder::TYPE_ASK
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "enabled" => array(
                    "ask" => array(
                        "question" => "Enabled : ",
                        "type" => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                        "default" => true
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "locked" => array(
                    "ask" => array(
                        "question" => "Locked : ",
                        "type" => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                        "default" => false
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "expiresAt" => array(
                    "ask" => array(
                        "question" => "Expiration date as Y-m-d H:i:s : ",
                        "type" => CommandAskBuilder::TYPE_ASK,
                        "default" => null
                    ),
                    "postProcess" => function ($result, $to, &$param, $cf, $color) {
                        if ($result !== null) {
                            $result = \DateTime::createFromFormat("Y-m-d H:i:s", $result);
                            
                            if (! ($result instanceof \DateTime)) {
                                $color->clear();
                                $color->addText("\n");
                                $color->addText($param["failure"], "failure");
                                $color->addText("\n");
                                $color->write();
                                
                                $param["active"] = false;
                            }
                        }
                        return $result;
                    },
                    "success" => "done",
                    "failure" => "failure"
                ),
                "credentialsExpireAt" => array(
                    "ask" => array(
                        "question" => "Expiration date as Y-m-d H:i:s : ",
                        "type" => CommandAskBuilder::TYPE_ASK,
                        "default" => null
                    ),
                    "postProcess" => function ($result, $to, &$param, $cf, $color) {
                        if ($result !== null) {
                            $result = \DateTime::createFromFormat("Y-m-d H:i:s", $result);
                            
                            if (! ($result instanceof \DateTime)) {
                                $color->clear();
                                $color->addText("\n");
                                $color->addText($param["failure"], "failure");
                                $color->addText("\n");
                                $color->write();
                                
                                $param["active"] = false;
                            }
                        }
                        return $result;
                    },
                    "success" => "done",
                    "failure" => "failure"
                ),
                "role" => array(
                    "preProcess" => function (&$param, $cf) {
                        $roles = $param["extra"]->findAllNames();
                        if (empty($roles)) {
                            $param["active"] = false;
                        } else {
                            $param["ask"]["limit"] = $roles;
                            $param["extraNames"] = $roles;
                            $param["active"] = true;
                        }
                        
                        return $param;
                    },
                    "ask" => array(
                        "question" => "Roles : ",
                        "default" => null,
                        "type" => CommandAskBuilder::TYPE_ASK_SELECT,
                        "option" => CommandAskBuilder::OPTION_ASK_MULTI_SELECT
                    ),
                    "extra" => $this->roleProvider,
                    "success" => "done",
                    "failure" => "failure",
                    "postProcess" => function ($result, &$to, &$param, $cf, $color) {
                        $roles = array();
                        $provider = $param["extra"];
                        $rolesNames = $param["extraNames"];
                        $boolSuccess = true;
                        if ($result !== null) {
                            if (is_array($result)) {
                                foreach ($result as $value) {
                                    if (array_key_exists($value, $rolesNames)) {
                                        $tmpR = $provider->findOneByName($rolesNames[$value]);
                                        
                                        if ($tmpR instanceof RoleBuilder) {
                                            $roles[] = $tmpR->getRole();
                                        }
                                    }
                                }
                            } else if (array_key_exists($result, $rolesNames)) {
                                $tmpR = $provider->findOneByName($rolesNames[$result]);
                                
                                if ($tmpR instanceof RoleBuilder) {
                                    $roles[] = $tmpR->getRole();
                                }
                            }
                            
                            foreach ($roles as $role) {
                                if (! $to->addRole($role)) {
                                    $boolSuccess = false;
                                }
                            }
                        } else {
                            foreach ($to->getRoles() as $role) {
                                var_dump($role);
                                $tmpR = $provider->findOneByName($role);
                                var_dump($tmpR);
                                $to->removeRole($role);
                            }
                        }
                        
                        if (! $boolSuccess) {
                            $color->clear();
                            $color->addText("\n");
                            $color->addText($param["failure"], "failure");
                            $color->addText("\n");
                            $color->write();
                        } else {
                            $color->clear();
                            $color->addText("\n");
                            $color->addText($param["success"], "success");
                            $color->addText("\n");
                            $color->write();
                        }
                        
                        $param["active"] = false;
                    }
                )
            )
        );
        
        $userInst = $user->getUser();
        
        $userRoles = $userInst->getRoles();
        $rolesNames = array();
        foreach ($userRoles as $role) {
            $rolesNames = $role->getName();
        }
        
        $valid = array(
            "username" => $userInst->getUsername(),
            "email" => $userInst->getEmail(),
            "password" => preg_replace("/./", "*", $userInst->getPlainPassword()),
            "salt" => $userInst->getSalt(),
            "confirmation token" => $userInst->getConfirmationToken(),
            "enabled" => $userInst->isEnabled(),
            "locked" => $userInst->isLocked(),
            "roles" => $rolesNames
        );
        
        if ($commandFacade->getConfirmation($valid)) {
            $this->userManager->persist($user);
        }
    }
}