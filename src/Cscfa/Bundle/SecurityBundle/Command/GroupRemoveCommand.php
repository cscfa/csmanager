<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
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
use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Doctrine\ORM\OptimisticLockException;

/**
 * GroupRemoveCommand class.
 *
 * The GroupRemoveCommand class purpose feater to
 * remove a Group that is registered into the database.
 *
 * @category Controller
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class GroupRemoveCommand extends ContainerAwareCommand
{

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
     * The GroupManager.
     *
     * This is used to manage the Group
     * logic and remove instance from
     * the database.
     *
     * @var GroupManager
     */
    protected $groupManager;

    /**
     * RoleRemoveCommand constructor.
     *
     * This constructor register a role provider
     * and a role manager. Also it call the parent
     * constructor.
     *
     * @param GroupManager  $groupManager  The group manager service to manage Group instance.
     * @param GroupProvider $groupProvider The group provider service to get Group instance into the database.
     */
    public function __construct(GroupManager $groupManager, GroupProvider $groupProvider)
    {
        $this->groupManager = $groupManager;
        
        $this->groupProvider = $groupProvider;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console cs:remove:group". It declare
     * only one optional argument name that represent the group
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('cs:remove:group')
            ->setDescription('Remove a group')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the group name?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will remove a
     * group from the database. This command will try
     * to get the group name to delete from the command
     * arguments or behind an interactive mode in the
     * shell.
     *
     * If the group exist, the command will ask for
     * user confirmation in order to remove. Finally,
     * the command will register an entity image
     * into the stackUpdate to offer backup possibilities.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     * 
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        $cf = new CommandColorFacade($output);
        $cf->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $cf->addColor("success", CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);
        
        $builder = new CommandAskBuilder(CommandAskBuilder::TYPE_ASK, "The group name : ", null, CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED, $this->groupProvider->findAllNames());
        $name = $commandFacade->getOrAsk("name", $builder);
        
        $group = $this->groupProvider->findOneByName($name);
        
        if ($group !== null && $commandFacade->getConfirmation(array("name" => $name))) {
            try {
                $this->groupManager->remove($group);
                $cf->clear();
                $cf->addText("\n");
                $cf->addText("\nDone\n", "success");
                $cf->addText("\n");
                $cf->write();
            } catch (OptimisticLockException $e) {
                $cf->clear();
                $cf->addText("\n");
                $cf->addText("\nAn error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine() . "\n", "error");
                $cf->addText("\n");
                $cf->write();
            }
        } else if ($group === null) {
            $cf->clear();
            $cf->addText("\n");
            $cf->addText("\nUnexisting group " . $name . ".\n", "error");
            $cf->addText("\n");
            $cf->write();
        }
    }
}
