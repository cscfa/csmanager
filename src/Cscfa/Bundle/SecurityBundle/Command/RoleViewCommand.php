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
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;

/**
 * RoleViewCommand class.
 *
 * The RoleViewCommand class purpose feater to
 * view all of the database registered Roles.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.1
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
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * RoleViewCommand constructor.
     *
     * This constructor register a role Provider.
     * Also it call the parent constructor.
     *
     * @param RoleProvider $roleProvider The Role provider service
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
     * @version Release: 1.1
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = array(
            'UUID'=>"getId",
            'Name'=>"getName",
            "created at"=>"getCreatedAt",
            "created by"=>"getCreatedBy",
            "last update"=>"getUpdatedAt",
            "updated by"=>"getUpdatedBy",
            "Child role"=>"getChild"
        );
        
        $table = new CommandTableBuilder();
        $table->setType($table::TYPE_OBJECT)
            ->setKeys($keys)
            ->setValues($this->roleProvider->findAll())
            ->render($output);
    }
}
