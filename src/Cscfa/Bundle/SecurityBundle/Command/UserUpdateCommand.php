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
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;
use Cscfa\Bundle\SecurityBundle\Util\Provider\RoleProvider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandAskBuilder;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PostProcessDateTime;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PreProcessRole;
use Cscfa\Bundle\SecurityBundle\Command\UpdateTool\PostProcessRoleArray;

/**
 * UserUpdateCommand class.
 *
 * The UserUpdateCommand class purpose feater to
 * update a User that is registered into the database.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class UserUpdateCommand extends ContainerAwareCommand
{
    /**
     * The user provider service.
     *
     * This allow to manage
     * the database for the
     * User instance.
     *
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * The user manager service.
     *
     * This allow to manage
     * the User instance logic.
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The role provider service.
     *
     * This allow to manage
     * the database for the
     * Role instances.
     *
     * @var RoleProvider
     */
    protected $roleProvider;

    /**
     * UserUpdateCommand constructor.
     *
     * The default UserUpdateCommand constructor.
     *
     * @param UserProvider $userProvider The user provider service that allow to
     *                                   manage the database
     * @param UserManager  $userManager  The user manager service that allow to
     *                                   manage the user logic
     * @param RoleProvider $roleProvider The role provider service that allow to
     *                                   manage the database
     */
    public function __construct(
        UserProvider $userProvider,
        UserManager $userManager,
        RoleProvider $roleProvider
    ) {
        $this->userProvider = $userProvider;
        $this->userManager = $userManager;
        $this->roleProvider = $roleProvider;
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console cs:update:user". It declare
     * only one optional argument username that represent the
     * user username to update.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('cs:update:user')
            ->setDescription('Update an user')
            ->addArgument(
                'username',
                InputArgument::OPTIONAL,
                "What's the user username to update?"
            );
    }

    /**
     * Command execution.
     *
     * The execution of the command will update an existing user
     * into the database.All of the needed informations will be ask
     * behind the shell as an interactive element.
     *
     * This command will check some behavior to grant the user
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
        list($username) = $commandFacade->getOrAskMulti(
            array(
                array(
                    'var' => 'username',
                    'question' => 'User username',
                    'type' => CommandAskBuilder::TYPE_ASK,
                    'option' => CommandAskBuilder::OPTION_ASK_AUTOCOMPLETED,
                    'completion' => $this->userProvider->findAllUsernames(),
                    'extra' => array(
                        'empty' => false,
                        'default' => false,
                    ),
                ),
            )
        );

        $user = $this->userProvider->findOneByUsername($username);

        if (!$user) {
            $outputColor = new CommandColorFacade($output);
            $outputColor->addColor(
                'error',
                CommandColorInterface::BLACK,
                CommandColorInterface::RED,
                null
            );
            $outputColor->clear();
            $outputColor->addText("\n");
            $outputColor->addText("\nUnexisting group ".$username.".\n", 'error');
            $outputColor->addText("\n");
            $outputColor->write();

            return;
        }

        $commandFacade->askATWIL(
            $user,
            'finish',
            'What to update',
            array(
                'username' => array(
                    'ask' => array(
                        'question' => 'Name : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'email' => array(
                    'ask' => array(
                        'question' => 'Email : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'password' => array(
                    'ask' => array(
                        'question' => 'Password : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                        'option' => CommandAskBuilder::OPTION_ASK_HIDDEN_RESPONSE,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'salt' => array(
                    'ask' => array(
                        'question' => 'Salt : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'confirmationToken' => array(
                    'ask' => array(
                        'question' => 'Confirmation token : ',
                        'type' => CommandAskBuilder::TYPE_ASK,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'enabled' => array(
                    'ask' => array(
                        'question' => 'Enabled : ',
                        'type' => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                        'default' => true,
                    ),
                    'success' => 'done',
                    'failure' => 'failure',
                ),
                'locked' => array(
                    'ask' => array(
                        'question' => 'Locked : ',
                        'type' => CommandAskBuilder::TYPE_ASK_CONFIRMATION,
                        'default' => false,
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
                'credentialsExpireAt' => array(
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

        $userInst = $user->getUser();

        $userRoles = $userInst->getRoles();

        $valid = array(
            'username' => $userInst->getUsername(),
            'email' => $userInst->getEmail(),
            'password' => preg_replace('/./', '*', $userInst->getPlainPassword()),
            'salt' => $userInst->getSalt(),
            'confirmation token' => $userInst->getConfirmationToken(),
            'enabled' => $userInst->isEnabled(),
            'locked' => $userInst->isLocked(),
            'roles' => $userRoles,
        );

        if ($commandFacade->getConfirmation($valid)) {
            $this->userManager->persist($user);
        }
    }
}
