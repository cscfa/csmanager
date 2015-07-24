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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;

/**
 * RoleViewCommand class.
 *
 * The RoleViewCommand class purpose feater to
 * view all of the database registered Roles.
 *
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleViewCommand extends ContainerAwareCommand
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
     * RoleViewCommand constructor.
     *
     * This constructor register a role Provider.
     * Also it call the parent constructor.
     *
     * @param RoleProvider $roleProvider            
     */
    public function __construct(RoleProvider $roleProvider)
    {
        // register role provider
        $this->roleProvider = $roleProvider;
        
        // call parent constructor
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This command have a common configuration that
     * only specify the command calling method as
     * "app/console csmanager:view:role".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:view:role')->setDescription('View all roles');
    }

    /**
     * Command execution.
     *
     * The execution of the command will display all
     * of the roles into a table with some informations.
     *
     * This command will prompt the role identity, the
     * role name, the creation date into Y-m-d H:i:s
     * format, the user creator username, the date of
     * the last update into the same format than creation
     * date, the user username that process the last
     * update and the child role name.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *            
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // getting all roles from the database
        $roles = $this->roleProvider->findAll();
        
        /*
         * if roles was correctly retreived,
         * display. An error can raise if
         * the role table is empty or if a
         * connection error occure.
         */
        if ($roles) {
            
            // initialise the table rows as empty array.
            $rows = array();
            
            // insert new row for each role
            foreach ($roles as $role) {
                $rows[] = $this->_buildRow($role);
            }
            
            // initialise the result table
            $table = new Table($output);
            // set the table header and rows
            $table->setHeaders(
                array(
                    'UUID',
                    'Name',
                    "created at",
                    "created by",
                    "last update",
                    "updated by",
                    "Child role"
                )
            )->setRows($rows);
            // render the result table
            $table->render();
        } else {
            $output->writeln("An error occures or the role table is empty.");
            return;
        }
    }
    
    /**
     * Build a row line.
     * 
     * This method build a row line
     * to display the role informations.
     * 
     * This line will contain id, name,
     * creation date, user creator
     * username, update date, user updator
     * username, child name.
     * 
     * @param Role $role The role to build row from.
     * 
     * @return array 
     */
    private function _buildRow(Role $role)
    {
        return array(
            $role->getId(),
            $role->getName(),
            $role->getCreatedAt()->format('Y-m-d H:i:s'),
            ($role->getCreatedBy() ? $role->getCreatedBy()->getUsername() : null),
            $role->getUpdatedAt()->format('Y-m-d H:i:s'),
            ($role->getUpdatedBy() ? $role->getUpdatedBy()->getUsername() : null),
            ($role->getChild() ? $role->getChild()->getName() : null)
        );
    }
}