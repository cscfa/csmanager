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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\GroupManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;

/**
 * GroupDebugCommand class.
 *
 * The GroupDebugCommand class purpose feater to
 * view the groups instance errors into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class GroupDebugCommand extends ContainerAwareCommand
{
    /**
     * The GroupManager.
     *
     * This variable is used to use
     * Group validation.
     *
     * @var GroupManager
     */
    protected $groupManager;

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
     * The RoleProvider.
     *
     * This variable is used to get
     * Role instance from the database.
     *
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * GroupDebugCommand constructor.
     *
     * This constructor register a Group
     * provider and a group manager. Also
     * it call the parent constructor.
     *
     * @param GroupManager  $groupManager  The group manager service
     * @param GroupProvider $groupProvider The group provider service
     * @param RoleProvider  $roleProvider  The role provider service
     */
    public function __construct(GroupManager $groupManager, GroupProvider $groupProvider, RoleProvider $roleProvider)
    {
        $this->groupProvider = $groupProvider;
        
        $this->groupManager = $groupManager;
        
        $this->roleProvider = $roleProvider;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:generate:group".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        // command configuration
        $this->setName('csmanager:debug:group')
            ->setDescription('Debuf the groups instances');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $canonicalTest = function ($val, $elm){if ($val == strtolower($elm->getName())) {return true;} else {return false;}};
        $timeTest = function ($val, $elm){if ($val instanceof \DateTime) {$current = new \DateTime();if ($val <= $current) {return true;} else {return false;}} else {return false;}};
        $timeTestOrNull = function ($val, $elm){if ($val instanceof \DateTime) {$current = new \DateTime();if ($val <= $current) {return true;} else {return false;}} else if ($val === null) {return true;} else {return false;}};
        $testUser = function ($val, $elm){if ($val instanceof User) {return true;} else {return false;}};
        $testUpdator = function ($val, $elm){if($elm->getUpdatedAt() === null && $val === null){return true;}if ($val instanceof User) {return true;} else {return false;}};
        
        $groups = $this->groupProvider->findAll();
        $commandFacade = new CommandFacade($input, $output, $this);
        list($rows, $error) = $commandFacade->debugMulti(
            $groups, 
            array(
                array("target"=>"getNameCanonical", "test"=>$canonicalTest),
                array("target"=>"getUpdatedAt", "test"=>$timeTestOrNull),
                array("target" => "getExpiresAt","test"=>$timeTestOrNull),
                array("target"=>"getCreatedAt", "test"=>$timeTest),
                array("target"=>"getCreatedBy", "test"=>$testUser),
                array("target"=>"getUpdatedBy", "test"=>$testUpdator)
            ),
            array("getId", "getName"),
            "<fg=green>V</fg=green>",
            "<fg=red>X</fg=red>"
        );
        
        $commandTable = new CommandTableBuilder();
        $commandTable->setType(CommandTableBuilder::TYPE_ARRAY)
            ->setValues($rows)
            ->setKeys(
                array(
                    "UUID"=>"getId",
                    "name"=>"getName",
                    "nameCanonical"=>"getNameCanonical",
                    "expire at"=>"getExpiresAt",
                    "updated at"=>"getUpdatedAt",
                    "created at"=>"getCreatedAt",
                    "updated by"=>"getUpdatedBy",
                    "created by"=>"getCreatedBy"
                )
            )
            ->render($output);
        
        $commandColor = new CommandColorFacade($output);
        $commandColor->addColor("error", CommandColorInterface::BLACK, CommandColorInterface::RED)
            ->addColor("success", CommandColorInterface::BLACK, CommandColorInterface::GREEN);
        $commandColor->addText("\nTotal errors : ");
        $commandColor->addText(" ".$error." ", ($error > 0 ? "error" : "success"));
        $commandColor->addText("\n");
        $commandColor->write();
    }
}