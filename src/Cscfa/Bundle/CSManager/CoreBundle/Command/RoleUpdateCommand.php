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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;
use Doctrine\ORM\OptimisticLockException;

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
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider
     */
    protected $roleProvider;

    /**
     * The RoleManager.
     *
     * This is used to manage the Role
     * logic and persist instance into
     * the database.
     *
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
     */
    protected $roleManager;

    /**
     * RoleUpdateCommand constructor.
     *
     * This constructor register a RoleProvider
     * and a role manager. Also it call
     * the parent constructor.
     *
     * @param RoleManager  $roleManager            
     * @param RoleProvider $roleProvider            
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
     * only one optional argument nae that represent the role
     * name to update.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:update:role')
            ->setDescription('Create and register new role')
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
            $roleName = $dialog->ask($output, 'Please enter the name of the role to update : ', null, $autoComplete);
        }
        
        // check if the role exist. If not, exit.
        if (! $this->roleManager->roleExists($roleName)) {
            $output->writeln("The role " . $roleName . " does not exist.");
            return;
        }
        
        // getting the role to update.
        $role = $this->roleProvider->findOneByName($roleName);
        
        // Getting a new name for the role. Loop while the given name is not valid or already exist.
        $loop = true;
        do {
            // getting a name for the role with it's own name as default.
            $name = $dialog->ask($output, 'Please enter a name for this role [<info>' . $role->getName() . '</info>]: ', $role->getName());
            
            // checking if the given name is valid and if none of other role already exist with this name.
            if (! $role->setName($name)) {
                if ($role->getLastError() === RoleBuilder::INVALID_ROLE_NAME) {
                    $output->writeln($name . " is not a valid name for a role. The role name can contain only case unsensitive A to Z letters and underscore.");
                } else if ($role->getLastError() === RoleBuilder::DUPLICATE_ROLE_NAME) {
                    $output->writeln($name . " already exist.");
                }
            } else {
                $loop = false;
            }
        } while ($loop);
        
        // Initialise the child to null.
        $child = null;
        // getting the question helper to allow interactive command
        $confirm = $this->getHelper('question');
        
        // ask if the user want to register a child for this role.
        $question = new ConfirmationQuestion('Want you assign a child for this role? ', false);
        // if the user want to register a child, ask for the child role name.
        if ($confirm->ask($input, $output, $question)) {
            
            // loop while none of the Role exist with the given role name.
            $loop = true;
            do {
                // ask for the child role name with last child role name as default
                $child = $dialog->ask(
                    $output, 'Please enter a child for this role [<info>' . ($role->getChild() ? $role->getChild()
                        ->getName() : null) . '</info>]: ', ($role->getChild() ? $role->getChild()
                            ->getName() : null), $autoComplete
                );
                
                // check if child role exist
                if ($this->roleManager->roleExists($child)) {
                    $loop = false;
                } else {
                    $output->writeln($child . " doesn't exist.");
                }
            } while ($loop);
        }
        
        // if child exist getting it from the database
        if ($child) {
            $child = $this->roleProvider->findOneByName($child)->getRole();
        }
        
        // setting the role parameter.
        $role->setChild($child);
        $role->setUpdatedAt(new \DateTime());
        
        // check if none of circular reference was created. Exit if one exist.
        if (! $role->setChild($child)) {
            $output->writeln($roleName . " create circular reference. Operation abort.");
            return;
        }
        
        // try to persisting the role into the database.
        try {
            $this->roleManager->persist($role);
            $output->writeln("Done");
        } catch (OptimisticLockException $e) {
            $output->writeln("An error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine());
        }
    }
}