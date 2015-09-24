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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\GroupProvider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\GroupBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;

/**
 * GroupAddCommand class.
 *
 * The GroupAddCommand class purpose feater to
 * generate a new group into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class GroupAddCommand extends ContainerAwareCommand
{

    /**
     * The GroupManager.
     *
     * This variable is used to use
     * Group validation.
     *
     * @var GroupManager
     */
    protected $groupManager;

    /**
     * The GroupProvider.
     *
     * This variable is used to get
     * Group instance from the database.
     *
     * @var GroupProvider
     */
    protected $groupProvider;

    /**
     * The RoleProvider.
     *
     * This variable is used to get
     * Role instance from the database.
     *
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * GroupAddCommand constructor.
     *
     * This constructor register a Group
     * provider and a group manager. Also
     * it call the parent constructor.
     *
     * @param GroupManager  $groupManager  The group manager service
     * @param GroupProvider $groupProvider The group provider service
     * @param RoleProvider  $roleProvider  The role provider service
     */
    public function __construct(GroupManager $groupManager, GroupProvider $groupProvider, RoleProvider $roleProvider)
    {
        // Register group provider
        $this->groupProvider = $groupProvider;
        
        // Register group manager
        $this->groupManager = $groupManager;
        
        $this->roleProvider = $roleProvider;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:generate:group". It declare
     * four optional arguments that are the name, the locked state,
     * the expiration date and a role collection. If this 
     * informations are omitted, they will be answer behind 
     * an interactive shell interface into the execute method.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:generate:group')
            ->setDescription('Create and register new group')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the group name?")
            ->addArgument('locked', InputArgument::OPTIONAL, "What's the group locked state?")
            ->addArgument('expiration', InputArgument::OPTIONAL, "What's the group expiration date?")
            ->addArgument('roles', InputArgument::IS_ARRAY, "What's the group roles?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will create a new group into
     * the database. Already existing two way to do that. In one
     * way, all of the needed informations was precised into the
     * command calling and the command will only check and register
     * the new group. In the second way, the shell will be an
     * interactive element that the user can use to specify the
     * informations to register.
     *
     * This command will check some behavior to grant the group
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
        
        $rolesNames = $this->roleProvider->findAllNames();
        if (empty($rolesNames)) {
            $rolesActive = false;
        } else {
            $rolesActive = true;
        }
        $multi = array(
            array(
                "var" => "name",
                "question" => "Group name",
                "type" => CommandAskBuilder::TYPE_ASK,
                "extra" => array(
                    "empty" => false,
                    "default" => false
                )
            ),
            array(
                "var" => "locked",
                "question" => "Locked group",
                "default" => false
            ),
            array(
                "var" => "expiration",
                "question" => "Group expiration as Y-m-d H:i:s",
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
                "question" => "Group roles",
                "type" => CommandAskBuilder::TYPE_ASK_SELECT,
                "limit" => $rolesNames,
                "option" => CommandAskBuilder::OPTION_ASK_MULTI_SELECT,
                "default" => null,
                "extra" => array(
                    "active" => $rolesActive,
                    "unactive" => array()
                )
            )
        );
        
        list ($name, $locked, $expire, $roles) = $commandFacade->getOrAskMulti($multi);
        
        $rolesSelected = array();
        foreach ($roles as $value) {
            $rolesSelected[] = $rolesNames[$value] . " ";
        }
        
        if ($commandFacade->getConfirmation(
            array(
                'name' => $name,
                'locked' => $locked,
                'expire' => $expire,
                'roles' => $rolesSelected
                )
        )) {
            
            $rolesArray = array();
            foreach ($roles as $value) {
                $tmpR = $this->roleProvider->findOneByName($rolesNames[$value]);
                
                if ($tmpR instanceof RoleBuilder) {
                    $rolesArray[] = $tmpR->getRole();
                }
            }
            
            $validating = array(
                "setName" => array(
                    $name,
                    "Naming error",
                    array(
                        GroupBuilder::EXISTING_NAME => "name exist",
                        GroupBuilder::INVALID_NAME => "invalid name"
                    )
                ),
                "setLocked" => array(
                    $locked,
                    "Locking error",
                    array(
                        GroupBuilder::NOT_BOOLEAN => "locked not boolean"
                    )
                ),
                "setExpiresAt" => array(
                    $expire,
                    "Expiration error",
                    array(
                        GroupBuilder::DATE_BEFORE_NOW => "Expiration date before now"
                    )
                ),
                "addRole" => array(
                    $rolesArray,
                    "Role error",
                    array(
                        GroupBuilder::HAS_ALREADY_ROLE => "Role already exist for this group",
                        GroupBuilder::UNEXISTING_ROLE => "Role doesn't exist in the database"
                    )
                )
            );
            
            $groupBuilder = $this->groupManager->getNewInstance();
            $isValid = $commandFacade->applyAndValidate($groupBuilder, $validating, "An error occured. Can't generate", "Generating succefull");
            
            if ($isValid) {
                $this->groupManager->persist($groupBuilder);
            }
        } else {
            return;
        }
    }
}
