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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;
use Doctrine\ORM\OptimisticLockException;

/**
 * UserRemoveCommand class.
 *
 * The UserRemoveCommand class purpose feater to
 * remove a User that is registered into the database.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class UserRemoveCommand extends ContainerAwareCommand
{
    /**
     * The user manager service.
     *
     * This variable is used to manage
     * User instance logic.
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The user provider service.
     *
     * This variable is used to manage
     * User instance storage into the
     * database.
     *
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * UserRemoveCommand constructor.
     *
     * This constructor register a doctrine
     *.entity manager. Also it call the parent
     * constructor.
     *
     * @param UserManager  $userManager  The user manager service to manage User instance logic.
     * @param UserProvider $userProvider The user provider service that manage User instance into the database.
     */
    public function __construct(UserManager $userManager, UserProvider $userProvider)
    {
        $this->userManager = $userManager;
        $this->userProvider = $userProvider;

        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console cs:remove:user". It declare
     * only one optional argument nae that represent the user
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('cs:remove:user')
            ->setDescription('Remove a user')
            ->addArgument('username', InputArgument::OPTIONAL, "What's the user name?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will remove a
     * user from the database. This command will try
     * to get the user name to delete from the command
     * arguments or behind an interactive mode in the
     * shell.
     *
     * If the user exist, the command will ask for
     * user confirmation in order to remove.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        $outputColor = new CommandColorFacade($output);
        $outputColor->addColor('error', CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $outputColor->addColor('success', CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);

        $builder = new CommandAskBuilder(
            CommandAskBuilder::TYPE_ASK,
            'The user name : ',
            null,
            CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
            $this->userProvider->findAllUsernames()
        );
        $name = $commandFacade->getOrAsk('username', $builder);

        $user = $this->userProvider->findOneByUsername($name);

        if ($user !== null && $commandFacade->getConfirmation(array('username' => $name))) {
            try {
                $this->userManager->remove($user);
                $outputColor->clear();
                $outputColor->addText("\n");
                $outputColor->addText("\nDone\n", 'success');
                $outputColor->addText("\n");
                $outputColor->write();
            } catch (OptimisticLockException $e) {
                $message = sprintf(
                    "\nAn error occures : [%s] %s\n\t In file : %s line %s\n",
                    $e->getCode(),
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                );
                $outputColor->clear();
                $outputColor->addText("\n");
                $outputColor->addText($message, 'error');
                $outputColor->addText("\n");
                $outputColor->write();
            }
        } elseif ($user === null) {
            $outputColor->clear();
            $outputColor->addText("\n");
            $outputColor->addText("\nUnexisting group ".$name.".\n", 'error');
            $outputColor->addText("\n");
            $outputColor->write();
        }
    }
}
