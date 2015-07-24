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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\Table;

/**
 * UserViewCommand class.
 *
 * The UserViewCommand class purpose feater to
 * view all of the database registered Users.
 *
 * @category Command
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserViewCommand extends ContainerAwareCommand
{

    /**
     * The doctrine entity manager.
     *
     * This variable is used to manage
     * User instance storage into the
     * database.
     *
     * @var EntityManager
     */
    protected $doctrineManager;

    /**
     * UserViewCommand constructor.
     *
     * This constructor register a doctrine
     *.entity manager. Also it call the parent
     * constructor.
     *
     * @param EntityManager $doctrineManager An entity manager to manage User instance into the database.
     */
    public function __construct(EntityManager $doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;
        
        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:view:user".
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:view:user')->setDescription('View all users');
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
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->doctrineManager->getRepository("CscfaCSManagerCoreBundle:User")->findAll();
        
        if ($users) {
            
            $rows = array();
            
            foreach ($users as $user) {
                $roleArray = $user->getRoles();
                $roles = "";
                
                foreach ($roleArray as $role) {
                    $roles .= $role . ", ";
                }
                
                $rows[] = array(
                    $user->getId(),
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->isEnabled(),
                    $user->isLocked(),
                    $user->isExpired(),
                    ($user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null),
                    $roles
                );
            }
            
            $table = new Table($output);
            $table->setHeaders(
                array(
                    'UUID',
                    'Name',
                    "Email",
                    "is enable",
                    "is locked",
                    "is expired",
                    "last login",
                    "Roles"
                )
            )->setRows($rows);
            
            $table->render();
        } else {
            $output->writeln("An error occures or the user table is empty.");
            return;
        }
    }
}