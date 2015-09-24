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
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;

/**
 * GroupViewCommand class.
 *
 * The GroupViewCommand class purpose feater to
 * view all of the database registered Groups.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class GroupViewCommand extends ContainerAwareCommand
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
     * GroupViewCommand constructor.
     *
     * This constructor register a group Provider.
     * Also it call the parent constructor.
     *
     * @param GroupProvider $groupProvider The Group provider service
     */
    public function __construct(GroupProvider $groupProvider)
    {
        $this->groupProvider = $groupProvider;
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This command have a common configuration that
     * only specify the command calling method as
     * "app/console csmanager:view:group".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:view:group')->setDescription('View all groups');
    }

    /**
     * Command execution.
     *
     * The execution of the command will display all
     * of the groups into a table with some informations.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *            
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = array(
            "UUID"=>"getId",
            "Name"=>"getName",
            "created at"=>"getCreatedAt",
            "Locked"=>"getLocked",
            "Expired"=>"isExpired"
        );
        
        $table = new CommandTableBuilder();
        $table->setType($table::TYPE_OBJECT)
            ->setKeys($keys)
            ->setValues($this->groupProvider->findAll())
            ->render($output);
    }
}
