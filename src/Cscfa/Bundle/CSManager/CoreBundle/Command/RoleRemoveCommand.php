<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Command
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;

/**
 * RoleRemoveCommand class.
 *
 * The RoleRemoveCommand class purpose feater to
 * remove a Role that is registered into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider
     */
    protected $roleProvider;

    /**
     * The RoleManager.
     *
     * This is used to manage the Role
     * logic and remove instance from
     * the database.
     *
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
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
     * behind "app/console csmanager:remove:role". It declare
     * only one optional argument name that represent the role
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:remove:role')
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
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // geting autocompletion string array to assist user to choose the right role
        $autoComplete = $this->roleManager->getRolesName();
        
        // getting the dialog helper to allow interactive command
        $dialog = $this->getHelper('dialog');
        
        // check if the name was precised into the command call. If not, answer the user about the role name.
        $name = $input->getArgument('name');
        if ($name) {
            $roleName = $name;
        } else {
            $roleName = $dialog->ask($output, 'Please enter the name of the role : ', null, $autoComplete);
        }
        
        // getting the role to delete
        $role = $this->roleProvider->findOneByName($roleName);
        
        // test if the role exist. If not, inform an exit. This can raise if the role doesn't exit or if an error occure.
        if ($role) {
            // ask the user if he is sure about the deletion procedure. If not, the command exit.
            $confirm = $this->getHelper('question');
            $question = new ConfirmationQuestion('<fg=red>Are you sure to delete ' . $roleName . ' ? </fg=red>', false);
            if ($confirm->ask($input, $output, $question)) {
                // Persist StackUpdate to store an image of the entity before modifycation and try to remove the role. Display error on failure state.
                try {
                    $this->roleManager->remove($role);
                    $output->writeln("Done");
                } catch (OptimisticLockException $e) {
                    $output->writeln("<error>An error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine() . "</error>");
                }
            } else {
                $output->writeln("<info>Aborted</info>");
            }
        } else {
            $output->writeln("<error>Unexisting role " . $roleName . ".</error>");
            return;
        }
    }
}