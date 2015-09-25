<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
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
use Cscfa\Bundle\SecurityBundle\Util\Provider\UserProvider;
use Cscfa\Bundle\ToolboxBundle\Builder\Command\CommandTableBuilder;

/**
 * UserViewCommand class.
 *
 * The UserViewCommand class purpose feater to
 * view all of the database registered Users.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.1
 * @link     http://cscfa.fr
 */
class UserViewCommand extends ContainerAwareCommand
{

    /**
     * The user provider service.
     *
     * This variable is used to manage
     * User instance storage into the
     * database.
     *
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * UserViewCommand constructor.
     *
     * This constructor register a doctrine
     *.entity manager. Also it call the parent
     * constructor.
     *
     * @param UserProvider $userProvider The user provider service to manage User instance into the database.
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
     * behind "app/console cs:view:user".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('cs:view:user')->setDescription('View all users');
    }

    /**
     * Command execution.
     *
     * The execution of the command will display all
     * of the users into a table with some informations.
     *
     * This command will prompt the user identity, the
     * user username, the user email, if the user is
     * enable, if the user is licked, if the user is expired
     * and his last login date.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see     \Symfony\Component\Console\Command\Command::execute()
     * @version Release: 1.1
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = array(
            'UUID'=>"getId",
            'Name'=>"getUsername",
            "Email"=>"getEmail",
            "is enable"=>"isEnabled",
            "is locked"=>"isLocked",
            "is expired"=>"isExpired",
            "last login"=>"getLastLogin",
            "Roles"=>"getRoles"
        );
        
        $table = new CommandTableBuilder();
        $table->setType($table::TYPE_OBJECT)
            ->setKeys($keys)
            ->setValues($this->userProvider->findAll())
            ->render($output);
    }
}
