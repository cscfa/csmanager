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
use Cscfa\Bundle\CSManager\CoreBundle\Util\Provider\UserProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;
use Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool\CanonicalTest;
use Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool\DateTimeTest;
use Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool\UpdateAtTest;
use Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool\UserInstanceTest;
use Cscfa\Bundle\CSManager\CoreBundle\Command\DebugTool\UpdatorTest;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Command\CommandColorInterface;

/**
 * UserDebugCommand class.
 *
 * The UserDebugCommand class purpose feater to
 * view the user instance errors into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserDebugCommand extends ContainerAwareCommand
{
    /**
     * The user provider service.
     * 
     * This parameter allow to
     * get the users instances
     * from the database.
     * 
     * @var UserProvider
     */
    protected $userProvider;
    
    /**
     * Default constructor.
     * 
     * This constructor register
     * the user provider service.
     * 
     * @param UserProvider $userProvider The user provider service.
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:debug:user".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:debug:user')
            ->setDescription('Debug the users instances');
    }

    /**
     * Command execution.
     *
     * This method display
     * a bug report for the
     * User instance.
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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        $users = $this->userProvider->findAll();
        $commandFacade = new CommandFacade($input, $output, $this);
        list($rows, $error) = $commandFacade->debugMulti(
            $users, 
            array(
                array("target"=>"getUsernameCanonical", "test"=>new CanonicalTest("getUsername")),
                array("target"=>"getEmailCanonical", "test"=>new CanonicalTest("getEmail")),
                array("target"=>"getLastLogin", "test"=>new DateTimeTest(DateTimeTest::BEFORE_NOW + DateTimeTest::CURRENT + DateTimeTest::ALLOW_NULL)),
                array("target"=>"getPasswordRequestedAt", "test"=>new DateTimeTest(DateTimeTest::BEFORE_NOW + DateTimeTest::CURRENT + DateTimeTest::ALLOW_NULL)),
                array("target"=>"getUpdatedAt", "test"=>new UpdateAtTest()),
                array("target"=>"getCreatedAt", "test"=>new DateTimeTest(DateTimeTest::BEFORE_NOW)),
                array("target"=>"getCreatedBy", "test"=>new UserInstanceTest()),
                array("target"=>"getUpdatedBy", "test"=>new UpdatorTest())
            ),
            array("getId", "getUsername"),
            "<fg=green>V</fg=green>",
            "<fg=red>X</fg=red>"
        );
        
        $commandTable = new CommandTableBuilder();
        $commandTable->setType(CommandTableBuilder::TYPE_ARRAY)
            ->setValues($rows)
            ->setKeys(
                array(
                    "UUID"=>"getId",
                    "username"=>"getUsername",
                    "usernameCanonical"=>"getUsernameCanonical",
                    "emailCanonical"=>"getEmailCanonical",
                    "last login"=>"getLastLogin",
                    "password request"=>"getPasswordRequestedAt",
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
