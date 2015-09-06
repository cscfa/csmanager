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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Doctrine\ORM\OptimisticLockException;

/**
 * UserRemoveCommand class.
 *
 * The UserRemoveCommand class purpose feater to
 * remove a User that is registered into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserRemoveCommand extends ContainerAwareCommand
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
     * UserRemoveCommand constructor.
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
     * behind "app/console csmanager:remove:user". It declare
     * only one optional argument nae that represent the user
     * name to delete.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $this->setName('csmanager:remove:user')
            ->setDescription('Remove a user')
            ->addArgument('username', InputArgument::OPTIONAL, "What's the user name?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will remove a
     * user from the database. This command will try
     * to get the user name to delete from the command
     * arguments or behind an interactive mode in the
     * shell.
     *
     * If the user exist, the command will ask for
     * user confirmation in order to remove.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     * 
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');
        $username = $input->getArgument('username');
        if ($username) {
            $name = $username;
        } else {
            $name = $dialog->ask($output, 'Please enter the name of the user : ');
        }
        
        $user = $this->doctrineManager->getRepository("CscfaCSManagerCoreBundle:User")->findOneByUsername($name);
        
        if ($user) {
            
            $confirm = $this->getHelper('question');
            $question = new ConfirmationQuestion('Are you sure to delete ' . $username . '?', false);
            if ($confirm->ask($input, $output, $question)) {
                try {
                    $this->doctrineManager->remove($user);
                    $this->doctrineManager->flush();
                    $output->writeln("Done");
                } catch (OptimisticLockException $e) {
                    $output->writeln("An error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine());
                }
            } else {
                $output->writeln("Aborted");
            }
        } else {
            $output->writeln("Unexisting user " . $username . ".");
            return;
        }
    }
}
