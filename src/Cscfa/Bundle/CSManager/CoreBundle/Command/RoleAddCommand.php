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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Builder\RoleBuilder;

/**
 * RoleAddCommand class.
 *
 * The RoleAddCommand class purpose feater to
 * generate a new user Role into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
     * RoleAddCommand constructor.
     *
     * This constructor register a Role
     * providder and a role manager. Also
     * it call the parent constructor.
     *
     * @param RoleManager  $roleManager            
     * @param RoleProvider $roleProvider            
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
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get all existing role name for autocompletion.
        $autoComplete = $this->roleManager->getRolesName();
        
        // get the dialog helper to create interactive interface.
        $dialog = $this->getHelper('dialog');
        
        // getting the role name from the input command. If not exist, use dialog to get it.
        $name = $input->getArgument('name');
        if ($name) {
            $roleName = $name;
        } else {
            // getting dialog to get role name.
            $roleName = $dialog->ask($output, 'Please enter the name of the role : ');
        }
        
        // getting the role child name from the input command. If not exist, use dialog to get it.
        $child = $input->getArgument('child');
        if ($child) {
            $roleChild = $child;
        } else {
            // getting the child name. By default the child is null, that's for empty child. This dialog use autocompletion for choose existing role.
            $roleChild = $dialog->ask($output, 'Please enter the name of the child role or press enter for null : ', null, $autoComplete);
        }
        
        // get new role instance from RoleManager.
        $newRole = $this->roleManager->getNewInstance();
        
        // assigning role parameters.
        if (! $newRole->setName(strtoupper($roleName))) {
            if ($newRole->getLastError() === RoleBuilder::DUPLICATE_ROLE_NAME) {
                $output->writeln("<error>" . $roleName . " already exist.</error>");
            } else if ($newRole->getLastError() === RoleBuilder::INVALID_ROLE_NAME) {
                $output->writeln("<error>" . $roleName . " is not a valid role name. The role name can contain only case unsensitive A to Z letters and underscore.</error>");
            }
            return;
        }
        $newRole->setCreatedAt(new \DateTime(), true);
        $newRole->setUpdatedAt(new \DateTime(), true);
        
        // getting role child or null.
        $child = $this->roleProvider->findOneByName($roleChild)->getRole();
        
        if (! $newRole->setChild($child)) {
            if ($newRole->getLastError() === RoleBuilder::INVALID_ROLE_INSTANCE_OF) {
                $output->writeln("<error>An error occured with the child creation.</error>");
            } else if ($newRole->getLastError() === RoleBuilder::CIRCULAR_REFERENCE) {
                $output->writeln("<error>" . $child->getName . " create a circular reference.</error>");
            }
            return;
        }
        
        // créate the new role into the database.
        try {
            $this->roleManager->persist($newRole);
            $output->writeln("Done");
        } catch (OptimisticLockException $e) {
            $output->writeln("<error>An error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine() . "</error>");
        }
    }
}