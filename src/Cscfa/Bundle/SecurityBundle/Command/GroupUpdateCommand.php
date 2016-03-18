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
use Symfony\Component\Console\Input\InputArgument;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PostProcessDateTime;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PreProcessRole;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PostProcessRoleArray;

/**
 * GroupUpdateCommand class.
 *
 * The GroupUpdateCommand class purpose feater to
 * update an existing group into the database.
 *
 * @category Command
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class GroupUpdateCommand extends ContainerAwareCommand
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
     * GroupAddCommand constructor.
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
     * behind "app/console cs:update:group". It declare
     * one optional arguments that are the name. If this
     * informations is omitted, it will be answer behind
     * an interactive shell interface into the execute method.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('cs:update:group')
            ->setDescription('Update and register new group')
            ->addArgument('name', InputArgument::OPTIONAL, "What's the group name?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will update an existing group
     * into the database.All of the needed informations will be ask
     * behind the shell as an interactive element.
     *
     * This command will check some behavior to grant the group
     * instance logic.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandFacade = new CommandFacade($input, $output, $this);
        list($name) = $commandFacade->getOrAskMulti(
            array(
                array(
                    'var' => 'name',
                    'question' => 'Group name',
                    'type' => CommandAskBuilder::TYPE_ASK,
                    'option' => CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
                    'completion' => $this->groupProvider->findAllNames(),
                    'extra' => array(
                        'empty' => false,
                        'default' => false,
                    ),
                ),
            )
        );

        $groupBuilder = $this->groupProvider->findOneByName($name);

        if (!$groupBuilder) {
            $outputColor = new CommandColorFacade($output);
            $outputColor->addColor('error', CommandColorInterface::BLACK, CommandColorInterface::RED, null);
            $outputColor->clear();
            $outputColor->addText("\n");
            $outputColor->addText("\nUnexisting group ".$name.".\n", 'error');
            $outputColor->addText("\n");
            $outputColor->write();

            return;
        }

        $commandFacade->askATWIL(
            $groupBuilder,
            'finish',
            'What to update',
            array(
                'name' => array(
                    'ask' => array(
                        'question' => 'Name : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'locked' => array(
                    'ask' => array(
                        'question' => 'Locked : ',
                        'type' => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'expiresAt' => array(
                    'ask' => array(
                        'question' => 'Expiration date as Y-m-d H:i:s : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                        'default' => null,
                    ),
                    'postProcess' => new PostProcessDateTime(),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'role' => array(
                    'preProcess' => new PreProcessRole('findAllNames'),
                    'ask' => array(
                        'question' => 'Roles : ',
                        'default' => null,
                        'type' => CommandAskBuilder::TYPE_ASK_SELECT,
                        'option' => CommandAskBuilder::OPTION_ASK_MULTI_SELECT,
                    ),
                    'extra' => $this->roleProvider,
                    'success' => 'done',
                    'failure' => 'failure',
                    'postProcess' => new PostProcessRoleArray(),
                ),
            )
        );

        $group = $groupBuilder->getGroup();

        $groupRoles = $group->getRoles();
        $rolesNames = array();
        foreach ($groupRoles as $role) {
            $rolesNames = $role->getName();
        }

        $valid = array(
            'name' => $group->getName(),
            'locked' => $group->isLocked(),
            'expire' => $group->getExpiresAt(),
            'roles' => $rolesNames,
        );

        if ($commandFacade->getConfirmation($valid)) {
            $this->groupManager->persist($groupBuilder);
        }
    }
}
