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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Builder\RoleBuilder;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;

/**
 * RoleAddCommand class.
 *
 * The RoleAddCommand class purpose feater to
 * generate a new user Role into the database.
 *
 * @category Controller
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.2
 * @link     http://cscfa.fr
 */
class RoleAddCommand extends ContainerAwareCommand
{

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
     * The RoleManager.
     *
     * This is used to manage the Role
     * logic and persist instance into
     * the database.
     *
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * RoleAddCommand constructor.
     *
     * This constructor register a Role
     * providder and a role manager. Also
     * it call the parent constructor.
     *
     * @param RoleManager  $roleManager  The Role manager service
     * @param RoleProvider $roleProvider The role provider service
     */
    public function __construct(RoleManager $roleManager, RoleProvider $roleProvider)
    {
        // Register role provider
        $this->roleProvider = $roleProvider;
        
        // Register role manager
        $this->roleManager = $roleManager;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:generate:role". It declare
     * two optional arguments that are the new role name and
     * a child name to reference. If this informations are
     * omitted, they will be answer behind an interactive
     * shell interface into the execute method.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:generate:role')
            ->setDescription('Create and register new role')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the role name?")
            ->addArgument('child', InputArgument::OPTIONAL, "What's the role child?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will create a new role into
     * the database. Already existing two way to do that. In one
     * way, all of the needed informations was precised into the
     * command calling and the command will only check and register
     * the new role. In the second way, the shell will be an
     * interactive element that the user can use to specify the
     * informations to register.
     *
     * This command will check some behavior to grant that the
     * new role doesn't already exists and doesn't create
     * circular reference to his childs. If one of this check
     * fail, the command will exit.
     *
     * This command automaticaly insert the creation date and
     * the update date. Featuring, an autocompletion is offer
     * to specify the child reference.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see     \Symfony\Component\Console\Command\Command::execute()
     * @version Release: 1.2
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $this->roleManager->getRolesName();
        $multi = array(
            array(
                "var" => "name",
                "question" => "Role name",
                "type" => CommandAskBuilder::TYPE_ASK,
                "extra" => array(
                    "empty" => false,
                    "default" => false
                )
            ),
            array(
                "var" => "child",
                "question" => "Role childs",
                "type" => CommandAskBuilder::TYPE_ASK_SELECT,
                "limit" => $limit,
                "default" => null,
                "extra" => array(
                    "active" => (empty($limit) ? false : true),
                    "unactive" => array()
                )
            )
        );
        $commandFacade = new CommandFacade($input, $output, $this);
        
        list ($name, $child) = $commandFacade->getOrAskMulti($multi);
        
        if (is_int($child)) {
            $childName = $limit[$child];
            $child = $this->roleProvider->findOneByName($childName)->getRole();
        } else {
            $childName = null;
            $child = null;
        }
        
        if ($commandFacade->getConfirmation(array("name" => $name, "childs" => $childName))) {
            
            $validating = array(
                "setName" => array(
                    $name,
                    "Naming error",
                    array(
                        RoleBuilder::DUPLICATE_ROLE_NAME => "name already exist",
                        RoleBuilder::INVALID_ROLE_NAME => "invalid name"
                    )
                ),
                "setChild" => array(
                    $child,
                    "Child error",
                    array(
                        RoleBuilder::CIRCULAR_REFERENCE => "circular reference creating",
                        RoleBuilder::INVALID_ROLE_INSTANCE_OF => "invalid role instance"
                    )
                )
            );
            
            $roleBuilder = $this->roleManager->getNewInstance();
            $isValid = $commandFacade->applyAndValidate($roleBuilder, $validating, "An error occured. Can't generate", "Generating succefull");
            
            if ($isValid) {
                $this->roleManager->persist($roleBuilder);
            }
        }
    }
}
