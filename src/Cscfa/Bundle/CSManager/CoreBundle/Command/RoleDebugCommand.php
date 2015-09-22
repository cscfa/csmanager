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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager;
use Symfony\Component\Console\Helper\Table;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;

/**
 * RoleDebugCommand class.
 *
 * The RoleDebugCommand class purpose feater to
 * debug all of the Role that are registered into
 * the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleDebugCommand extends ContainerAwareCommand
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
     * logic.
     *
     * @var Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
     */
    protected $roleManager;

    /**
     * RoleDebugCommand constructor.
     *
     * This constructor register a role provider
     * and a role manager. Also it call the parent
     * constructor.
     *
     * @param RoleProvider $RoleProvider The Role provider service
     * @param RoleManager  $roleManager  The Role manager service
     */
    public function __construct(RoleProvider $RoleProvider, RoleManager $roleManager)
    {
        // register role provider
        $this->roleProvider = $RoleProvider;
        
        // register role manager
        $this->roleManager = $roleManager;
        
        // call parent constructor
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This command have a common configuration that
     * only specify the command calling method as
     * "app/console csmanager:debug:role".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:debug:role')->setDescription('Debug all roles');
    }

    /**
     * Command execution.
     *
     * The execution of the command will check some behavior
     * about the roles. In order to proceed, all of the roles
     * will be catch from the database.
     *
     * The chack that will be done have to goal to preserve
     * the roles logic. To begin, the command will check if
     * all of the dates are in logic state, so, if none
     * of them are so higher than the present day and if
     * a creation date is under an update date.
     *
     * After, the command will check if an user is registered
     * as ignitor of a creation action or update action.
     *
     * To finish, this command will determine if none of
     * the roles have a circular reference to it childs.
     *
     * Featuring, this command render a progress bar to
     * inform about it progress on heavy role table, and
     * finally display a result table that present for
     * each role the state of each checking points.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        // getting all of the roles to inspect them
        $roles = $this->roleProvider->findAll();
        
        // if a request failure exist the roles will be empty or null. It can be because the role table is empty or because an error occured
        if ($roles) {
            // initialise render table rows to empty array
            $rows = array();
            
            // display a progress bar to inform about the operation progress
            $progress = $this->getHelperSet()->get('progress');
            // start the progress bar displaying and initialise it to the roles count as maximum value
            $progress->start($output, count($roles));
            
            // start inspection of all roles
            foreach ($roles as $role) {
                // initialise the current debug state array as empty array
                $currentDebug = array();
                
                // Check if the date are in correct things. This will register an error if one of them are more advanced than now orif the creation rise after the update.
                if ($role->getCreatedAt() <= $role->getUpdatedAt() && $role->getCreatedAt() < new \DateTime() && $role->getUpdatedAt() < new \DateTime()) {
                    $currentDebug["dateValidity"] = "<fg=green>valid</fg=green>";
                } else {
                    $currentDebug["dateValidity"] = "<error>error</error>";
                }
                
                // Check if an user is registered for the role creation.
                if ($role->getCreatedBy()) {
                    $currentDebug["creator"] = "<fg=green>valid</fg=green>";
                } else {
                    $currentDebug["creator"] = "<error>error</error>";
                }
                
                // check if an user is registered for the role update.
                if ($role->getCreatedAt() < $role->getUpdatedAt() && $role->getUpdatedBy()) {
                    $currentDebug["updator"] = "<fg=green>valid</fg=green>";
                } else if ($role->getCreatedAt() < $role->getUpdatedAt()) {
                    $currentDebug["updator"] = "<error>error</error>";
                } else {
                    $currentDebug["updator"] = "<fg=green>valid</fg=green>";
                }
                
                // check if the role have a circular reference with his childs.
                if (! $this->roleManager->hasCircularReference($role)) {
                    $currentDebug["reference"] = "<fg=green>valid</fg=green>";
                } else {
                    $currentDebug["reference"] = "<error>error</error>";
                }
                
                // register all of the results into a table row
                $rows[] = array(
                    $role->getId(),
                    $role->getName(),
                    $currentDebug["dateValidity"],
                    $currentDebug["creator"],
                    $currentDebug["updator"],
                    $currentDebug["reference"]
                );
                // advance the progress bar.
                $progress->advance();
            }
            // stop to display the progress bar.
            $progress->finish();
            
            // initialise the result rendered table with the header and the previous created rows
            $table = new Table($output);
            $table->setHeaders(
                array(
                    'UUID',
                    'Name',
                    "date validity",
                    "creator validity",
                    "updator validity",
                    "no circular reference"
                )
            )->setRows($rows);
            
            // render the result table
            $table->render();
        } else {
            $output->writeln("An error occures or the role table is empty.");
            return;
        }
    }
}
