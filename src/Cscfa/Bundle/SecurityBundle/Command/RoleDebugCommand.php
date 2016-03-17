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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UpdateAtTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\DateTimeTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UserInstanceTest;
use Cscfa\Bundle\SecurityBundle\Command\DebugTool\UpdatorTest;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;

/**
 * RoleDebugCommand class.
 *
 * The RoleDebugCommand class purpose feater to
 * debug all of the Role that are registered into
 * the database.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @version  Release: 1.1
 *
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
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * The RoleManager.
     *
     * This is used to manage the Role
     * logic.
     *
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * RoleDebugCommand constructor.
     *
     * This constructor register a role provider
     * and a role manager. Also it call the parent
     * constructor.
     *
     * @param RoleProvider $roleProvider The Role provider service
     * @param RoleManager  $roleManager  The Role manager service
     */
    public function __construct(RoleProvider $roleProvider, RoleManager $roleManager)
    {
        $this->roleProvider = $roleProvider;

        $this->roleManager = $roleManager;

        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This command have a common configuration that
     * only specify the command calling method as
     * "app/console cs:debug:role".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('cs:debug:role')->setDescription('Debug all roles');
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
     * @see     \Symfony\Component\Console\Command\Command::execute()
     *
     * @version Release: 1.1
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roles = $this->roleProvider->findAll();
        $commandFacade = new CommandFacade($input, $output, $this);
        list($rows, $error) = $commandFacade->debugMulti(
            $roles,
            array(
                array('target' => 'getUpdatedAt', 'test' => new UpdateAtTest()),
                array('target' => 'getCreatedAt', 'test' => new DateTimeTest(DateTimeTest::BEFORE_NOW)),
                array('target' => 'getCreatedBy', 'test' => new UserInstanceTest()),
                array('target' => 'getUpdatedBy', 'test' => new UpdatorTest()),
            ),
            array('getId', 'getName'),
            '<fg=green>V</fg=green>',
            '<fg=red>X</fg=red>'
        );

        foreach ($rows as $key => $row) {
            $result = $this->roleManager->hasCircularReference($roles[$key]);

            if ($result) {
                $row['circular'] = '<fg=red>X</fg=red>';
            } else {
                $row['circular'] = '<fg=green>V</fg=green>';
            }

            $rows[$key] = $row;
        }

        $commandTable = new CommandTableBuilder();
        $commandTable->setType(CommandTableBuilder::TYPE_ARRAY)
            ->setValues($rows)
            ->setKeys(
                array(
                    'UUID' => 'getId',
                    'Name' => 'getName',
                    'updated at' => 'getUpdatedAt',
                    'created at' => 'getCreatedAt',
                    'updated by' => 'getUpdatedBy',
                    'created by' => 'getCreatedBy',
                    'Circular reference' => 'circular',
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
