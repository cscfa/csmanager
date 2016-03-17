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

use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\CanonicalTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UpdateAtTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\DateTimeTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UserInstanceTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UpdatorTest;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * GroupDebugCommand class.
 *
 * The GroupDebugCommand class purpose feater to
 * view the groups instance errors into the database.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     * behind "app/console cs:debug:group".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('cs:debug:group')
            ->setDescription('Debug the groups instances');
    }

    /**
     * Command execution.
     *
     * This method display
     * a bug report for the
     * Group instance.
     *
     * The validation given
     * view for canonical name,
     * update date, expiration
     * date, creation date,
     * creation user instance,
     * update user instance.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groups = $this->groupProvider->findAll();
        $commandFacade = new CommandFacade($input, $output, $this);
        list($rows, $error) = $commandFacade->debugMulti(
            $groups,
            array(
                array('target' => 'getNameCanonical', 'test' => new CanonicalTest('getName')),
                array('target' => 'getUpdatedAt', 'test' => new UpdateAtTest()),
                array('target' => 'getExpiresAt', 'test' => new DateTimeTest(DateTimeTest::ALL_ALLOWED)),
                array('target' => 'getCreatedAt', 'test' => new DateTimeTest(DateTimeTest::BEFORE_NOW)),
                array('target' => 'getCreatedBy', 'test' => new UserInstanceTest()),
                array('target' => 'getUpdatedBy', 'test' => new UpdatorTest()),
            ),
            array('getId', 'getName'),
            '<fg=green>V</fg=green>',
            '<fg=red>X</fg=red>'
        );

        $commandTable = new CommandTableBuilder();
        $commandTable->setType(CommandTableBuilder::TYPE_ARRAY)
            ->setValues($rows)
            ->setKeys(
                array(
                    'UUID' => 'getId',
                    'name' => 'getName',
                    'nameCanonical' => 'getNameCanonical',
                    'expire at' => 'getExpiresAt',
                    'updated at' => 'getUpdatedAt',
                    'created at' => 'getCreatedAt',
                    'updated by' => 'getUpdatedBy',
                    'created by' => 'getCreatedBy',
                )
            )
            ->render($output);

        $commandColor = new CommandColorFacade($output);
        $commandColor->addColor('error', CommandColorInterface::BLACK, CommandColorInterface::RED)
            ->addColor('success', CommandColorInterface::BLACK, CommandColorInterface::GREEN);
        $commandColor->addText("\nTotal errors : ");
        $commandColor->addText(' '.$error.' ', ($error > 0 ? 'error' : 'success'));
        $commandColor->addText("\n");
        $commandColor->write();
    }
}
