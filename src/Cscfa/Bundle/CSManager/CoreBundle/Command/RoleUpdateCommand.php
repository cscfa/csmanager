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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Command\UpdateTool\PreProcessRole;
use Cscfa\Bundle\CSManager\CoreBundle\Command\UpdateTool\PostProcessRole;

/**
 * RoleUpdateCommand class.
 *
 * The RoleUpdateCommand class purpose feater to
 * update a Role that is registered into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.1
 * @link     http://cscfa.fr
 */
class RoleUpdateCommand extends ContainerAwareCommand
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
     * RoleUpdateCommand constructor.
     *
     * This constructor register a RoleProvider
     * and a role manager. Also it call
     * the parent constructor.
     *
     * @param RoleManager  $roleManager  The Role manager service
     * @param RoleProvider $roleProvider The Role provider service
     */
    public function __construct(RoleManager $roleManager, RoleProvider $roleProvider)
    {
        // register role manager
        $this->roleManager = $roleManager;
        
        // register role provider
        $this->roleProvider = $roleProvider;
        
        // call parent constructor
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:update:role". It declare
     * only one optional argument name that represent the role
     * name to update.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:update:role')
            ->setDescription('Update a role')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the role name to update?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will update a role
     * into the database. This command will try to find
     * the name of the role to update into the command
     * calling arguments or will ask user behnd an
     * interactive shell to get the role name to update.
     *
     * This command purpose to update the role name and
     * the role child. In order to register, it will check
     * if the new name not already exist and if the role
     * child already exist. Also, it check if no circular
     * reference are created with the role childs.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *            
     * @see     \Symfony\Component\Console\Command\Command::execute()
     * @version Release: 1.1
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        list ($name) = $commandFacade->getOrAskMulti(
            array(
                array(
                    "var" => "name",
                    "question" => "Role name",
                    "type" => CommandAskBuilder::TYPE_ASK,
                    "option" => CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
                    "completion" => $this->roleManager->getRolesName(),
                    "extra" => array(
                        "empty" => false,
                        "default" => false
                    )
                )
            )
        );
        
        $role = $this->roleProvider->findOneByName($name);
        if (! $role) {
            $cf = new CommandColorFacade($output);
            $cf->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED, null);
            $cf->clear();
            $cf->addText("\n");
            $cf->addText("\nUnexisting role " . $name . ".\n", "error");
            $cf->addText("\n");
            $cf->write();
            
            return;
        }
        
        $commandFacade->askATWIL(
            $role, 
            "finish", 
            "What to update", 
            array(
                "name" => array(
                    "ask" => array(
                        "question" => "Name : ",
                        "type" => CommandAskBuilder::TYPE_ASK
                    ),
                    "success" => "done",
                    "failure" => "failure"
                ),
                "child" => array(
                    "preProcess" => new PreProcessRole("findAllNames"),
                    "ask" => array(
                        "question" => "Child : ",
                        "default" => null,
                        "type" => CommandAskBuilder::TYPE_ASK_SELECT
                    ),
                    "extra" => $this->roleProvider,
                    "success" => "done",
                    "failure" => "failure",
                    "postProcess" => new PostProcessRole("setChild")
                )
            )
        );
        
        $valid = array(
            "name" => $role->getName(),
            "child" => ($role->getChild() !== null ? $role->getChild()->getName() : null)
        );
        
        if ($commandFacade->getConfirmation($valid)) {
            $this->roleManager->persist($role);
        }
    }
}
