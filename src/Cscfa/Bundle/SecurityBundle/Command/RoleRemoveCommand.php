<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Command
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\SecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Doctrine\ORM\OptimisticLockException;

/**
 * RoleRemoveCommand class.
 *
 * The RoleRemoveCommand class purpose feater to
 * remove a Role that is registered into the database.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @version  Release: 1.1
 *
 * @link     http://cscfa.fr
 */
class RoleRemoveCommand extends ContainerAwareCommand
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
     * logic and remove instance from
     * the database.
     *
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * RoleRemoveCommand constructor.
     *
     * This constructor register a role provider
     * and a role manager. Also it call the parent
     * constructor.
     *
     * @param RoleManager  $roleManager  A RoleManager to manage Role user instance.
     * @param RoleProvider $roleProvider A RoleProvider to get Role instance from the database.
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
     * behind "app/console cs:remove:role". It declare
     * only one optional argument name that represent the role
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        // command configuration
        $this->setName('cs:remove:role')
            ->setDescription('Remove a role')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the role name?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will remove a
     * role from the database. This command will try
     * to get the role name to delete from the command
     * arguments or behind an interactive mode in the
     * shell.
     *
     * If the role exist, the command will ask for
     * user confirmation in order to remove. Finally,
     * the command will register an entity image
     * into the stackUpdate to offer backup possibilities.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see     \Symfony\Component\Console\Command\Command::execute()
     *
     * @version Release: 1.1
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        $ouputColor = new CommandColorFacade($output);
        $ouputColor->addColor('error', CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $ouputColor->addColor('success', CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);

        $builder = new CommandAskBuilder(
            CommandAskBuilder::TYPE_ASK,
            'The role name : ',
            null,
            CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
            $this->roleManager->getRolesName()
        );
        $name = $commandFacade->getOrAsk('name', $builder);

        $role = $this->roleProvider->findOneByName($name);

        if ($role !== null && $commandFacade->getConfirmation(array('name' => $name))) {
            try {
                $this->roleManager->remove($role);
                $ouputColor->clear();
                $ouputColor->addText("\n");
                $ouputColor->addText("\nDone\n", 'success');
                $ouputColor->addText("\n");
                $ouputColor->write();
            } catch (OptimisticLockException $e) {
                $message = sprintf(
                    "\nAn error occures : [%s] %s\n\t In file : %s line %s\n",
                    $e->getCode(),
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                );

                $ouputColor->clear();
                $ouputColor->addText("\n");
                $ouputColor->addText($message, 'error');
                $ouputColor->addText("\n");
                $ouputColor->write();
            }
        } elseif ($role === null) {
            $ouputColor->clear();
            $ouputColor->addText("\n");
            $ouputColor->addText("\nUnexisting role ".$name.".\n", 'error');
            $ouputColor->addText("\n");
            $ouputColor->write();
        }
    }
}
