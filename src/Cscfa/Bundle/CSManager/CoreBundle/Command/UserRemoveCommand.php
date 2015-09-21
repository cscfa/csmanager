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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\UserManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;

/**
 * UserRemoveCommand class.
 *
 * The UserRemoveCommand class purpose feater to
 * remove a User that is registered into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * behind "app/console csmanager:remove:user". It declare
     * only one optional argument nae that represent the user
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:remove:user')
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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        $cf = new CommandColorFacade($output);
        $cf->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED, null);
        $cf->addColor("success", CommandColorInterface::BLACK, CommandColorInterface::GREEN, null);
        
        $builder = new CommandAskBuilder(CommandAskBuilder::TYPE_ASK, "The user name : ", null, CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED, $this->userProvider->findAllUsernames());
        $name = $commandFacade->getOrAsk("username", $builder);
        
        $user = $this->userProvider->findOneByUsername($name);
        
        if ($user !== null && $commandFacade->getConfirmation(array("username" => $name))) {
            try {
                $this->userManager->remove($user);
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
        } else if ($user === null) {
            $cf->clear();
            $cf->addText("\n");
            $cf->addText("\nUnexisting group " . $name . ".\n", "error");
            $cf->addText("\n");
            $cf->write();
        }
    }
}
