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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\OptimisticLockException;

/**
 * UserAddCommand class.
 *
 * The UserAddCommand class purpose feater to
 * generate a new user into the database.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserAddCommand extends ContainerAwareCommand
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
     * The EncoderFactoryInterface.
     * 
     * This variable is used to process
     * the user password encodage.
     * 
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * UserAddCommand constructor.
     *
     * This constructor register a doctrine
     *.entity manager and an encoder factory. 
     *Also it call the parent constructor.
     *
     * @param EntityManager           $doctrineManager An entity manager to manage User instance into the database.
     * @param EncoderFactoryInterface $encoderFactory  A security encoder factory to encode user password.
     */
    public function __construct(EntityManager $doctrineManager, EncoderFactoryInterface $encoderFactory)
    {
        $this->doctrineManager = $doctrineManager;
        $this->encoderFactory = $encoderFactory;
        
        parent::__construct();
    }


    /**
     * Command configuration.
     *
     * This configuration purpose that calling this command
     * behind "app/console csmanager:add:user". It declare
     * four optional arguments to precise all of the user
     * attributes, and four options.
     * 
     * The defined argumants are username, email, password
     * and roles.
     * 
     * The defined options are enabled, salt, confirmation
     * token and expiration date.
     *
     * @see    \Symfony\Component\Console\Command\Command::configure()
     * @return void
     */
    protected function configure()
    {
        $defaultStrong = true;
        
        $this->setName('csmanager:generate:user')
            ->setDescription('Create and register new user')
            ->addArgument('username', InputArgument::OPTIONAL, "What's the username?")
            ->addArgument('email', InputArgument::OPTIONAL, "What's the user mail?")
            ->addArgument('password', InputArgument::OPTIONAL, "What's the user password?")
            ->addOption('enabled', '-en', InputOption::VALUE_OPTIONAL, "If the user is enabled?", true)
            ->addOption('salt', '-sa', InputOption::VALUE_OPTIONAL, "What's the user salt?", base64_encode(utf8_encode(openssl_random_pseudo_bytes(10, $defaultStrong))))
            ->addOption('confirmationToken', '-co', InputOption::VALUE_OPTIONAL, "What's the user confirmationToken?", base64_encode(utf8_encode(openssl_random_pseudo_bytes(30, $defaultStrong))))
            ->addOption('expiresAt', '-ex', InputOption::VALUE_OPTIONAL, "What's the user account expiration date?", null)
            ->addArgument('roles', InputArgument::IS_ARRAY, "What's the user roles?");
    }

    /**
     * Command execution.
     *
     * The execution of the command will create a new user into
     * the database by using the given arguments and options or
     * by using an interactive shell to catch inforations.
     * 
     * In order to proceed, this method will check the Role 
     * existance before inserting it into the user instance.
     * If it not exist, will exit.
     *
     * @param InputInterface  $input  The common command input
     * @param OutputInterface $output The common command output
     *
     * @see    \Symfony\Component\Console\Command\Command::execute()
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');
        
        $enabled = $input->getOption('enabled');
        $salt = $input->getOption('salt');
        $confirmationToken = $input->getOption('confirmationToken');
        $expiresAt = $input->getOption('expiresAt');
        $defaultStrong = true;
        
        $dialog = $this->getHelper('dialog');
        
        if (! $username) {
            $username = $dialog->ask($output, 'Please enter the user name : ');
        }
        if (! $email) {
            $email = $dialog->ask($output, 'Please enter the user email : ');
        }
        if (! $password) {
            $password = $dialog->askHiddenResponse($output, 'Please enter the user password : ');
        }
        if (! $roles) {
            do {
                $roleElm = $dialog->ask($output, 'Please enter one of the user role or let empty to continue : ');
                if ($roleElm) {
                    $roleEntity = $this->doctrineManager->getRepository("CscfaCSManagerCoreBundle:Role")->findOneByName($roleElm);
                    
                    do {
                        if ($roleEntity) {
                            $roles[] = $roleEntity;
                        } else {
                            $output->writeln("Undefined role.");
                        }
                        
                        if ($roleEntity->getChild()) {
                            $roleEntity = $roleEntity->getChild();
                            $output->writeln($roleEntity->getName() . " implicitely.");
                        } else {
                            $roleEntity = false;
                        }
                    } while ($roleEntity);
                }
            } while ($roleElm);
        }
        
        if (! $enabled) {
            $enabled = true;
        }
        if (! $salt) {
            $salt = base64_encode(utf8_encode(openssl_random_pseudo_bytes(10, $defaultStrong)));
        }
        if (! $confirmationToken) {
            $salt = base64_encode(utf8_encode(openssl_random_pseudo_bytes(30, $defaultStrong)));
        }
        
        $user = new User();
        $user->setUsername($username)
            ->setEmail($email)
            ->setEnabled($enabled)
            ->setSalt($salt)
            ->setConfirmationToken($confirmationToken)
            ->setExpiresAt($expiresAt)
            ->setUsernameCanonical(strtolower($username))
            ->setEmailCanonical(strtolower($email))
            ->setRoles($roles);
        
        $hash = $this->encoderFactory->getEncoder($user)->encodePassword($password, null);
        $user->setPassword($hash);
        
        $this->doctrineManager->persist($user);
        
        try {
            $this->doctrineManager->flush();
            $output->writeln("Done");
        } catch (OptimisticLockException $e) {
            $output->writeln("An error occures : [" . $e->getCode() . "] " . $e->getMessage() . "\n\t In file : " . $e->getFile() . " line " . $e->getLine());
        }
    }
}