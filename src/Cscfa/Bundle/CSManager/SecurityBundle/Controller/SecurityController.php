<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Controller
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\CSManager\ConfigBundle\Entity\Preference;
use Cscfa\Bundle\CSManager\ConfigBundle\Entity\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin\SignInObject;
use Symfony\Component\Form\Form;
use Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin\SigninHydrator;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Person;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * SecurityController class.
 *
 * The SecurityController provide
 * method to manage the security.
 *
 * @category Controller
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class SecurityController extends Controller
{

    /**
     * confirmationByMailAction
     * 
     * This method enable an user
     * by giving the id and the
     * confirmation token behind
     * an http get request
     * 
     * @param User   $user              - the user id
     * @param string $confirmationToken - the confirmation token
     * 
     * @template
     */
    public function confirmationByMailAction(Request $request, User $user, $confirmationToken)
    {
        $confirmationToken = urldecode($confirmationToken);
        
        if (! $this->getPreference()->getConfiguration()->getSignInAllowed() || 
            ! $this->getPreference()->getConfiguration()->getSignInVerifyEmail()) {
            throw $this->createAccessDeniedException("Access forbiden due to signin desallowed");
        }
        
        if ($user->getConfirmationToken() == $confirmationToken) {
            $user->setEnabled(true);
            $this->getDoctrine()
                ->getManager()
                ->persist($user);
            $this->getDoctrine()
                ->getManager()
                ->flush();
            
            $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            $this->get("security.context")->setToken($token);
            
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            
            return array(
                "success" => true
            );
        } else {
            return array(
                "success" => false
            );
        }
    }

    /**
     * RegisterAction
     * 
     * This method allow to manage
     * the registering logic
     * 
     * @param Request $request
     * 
     * @template
     */
    public function registerAction(Request $request)
    {
        $preference = $this->getPreference();
        $signinAllowed = $preference->getConfiguration()->getSignInAllowed();
        
        if ($signinAllowed) {
            $signin = new SignInObject();
            $signinForm = $this->createForm("signin", $signin, array(
                "action" => $this->generateUrl('register')
            ));
            
            $signinForm->handleRequest($request);
            
            if ($signinForm->isValid()) {
                $errors = false;
                $data = $signinForm->getData();
                $manager = $this->getDoctrine()->getManager();
                $hydrator = new SigninHydrator();
                
                if ($data instanceof SignInObject) {
                    $groups = $data->getGroups();
                    
                    $user = null;
                    
                    if (in_array("Default", $groups)) {
                        $userManager = $this->get("core.manager.user_manager");
                        if ($hydrator->hydrateBase($manager, $userManager, $signinForm, $user, $preference)) {
                            $errors = true;
                        }
                        
                        if (! $errors) {
                            
                            $person = new Person();
                            $manager->persist($person);
                            
                            $person->setUser($user->getUser());
                            
                            if (in_array("Phone", $groups)) {
                                $hydrator->hydratePhone($manager, $signinForm, $person);
                            }
                            
                            if (in_array("Yourself", $groups)) {
                                $hydrator->hydrateYourself($signinForm, $person);
                            }
                            
                            if (in_array("Company", $groups)) {
                                $hydrator->hydrateCompany($manager, $signinForm, $person);
                            }
                            
                            if (in_array("Address", $groups)) {
                                $hydrator->hydrateCompany($manager, $signinForm, $person);
                            }
                        }
                    }
                }
                
                if (! $errors) {
                    $manager->flush();
                    
                    if ($preference->getConfiguration()->getSignInVerifyEmail() && $user instanceof UserBuilder) {
                        $message = \Swift_Message::newInstance()->setSubject('CSManager : signin confirmation email')
                            ->setFrom($preference->getEmailSender())
                            ->setTo($user->getEmail())
                            ->setBody($this->renderView('CscfaCSManagerSecurityBundle:mail:signinConfirmation.html.twig', array(
                            'token' => urlencode($user->getConfirmationToken()),
                            'id' => $user->getId(),
                            'preference' => $preference
                        )), 'text/html')
                            ->addPart($this->renderView('CscfaCSManagerSecurityBundle:mail:signinConfirmation.txt.twig', array(
                            'token' => urlencode($user->getConfirmationToken()),
                            'id' => $user->getId(),
                            'preference' => $preference
                        )), 'text/plain');
                        
                        $this->get('mailer')->send($message);
                    } else {
                        $message = \Swift_Message::newInstance()->setSubject('CSManager : signin confirmation email')
                            ->setFrom($preference->getEmailSender())
                            ->setTo($user->getEmail())
                            ->setBody($this->renderView('CscfaCSManagerSecurityBundle:mail:signin.html.twig', array(
                            'preference' => $preference
                        )), 'text/html')
                            ->addPart($this->renderView('CscfaCSManagerSecurityBundle:mail:signin.txt.twig', array(
                            'preference' => $preference
                        )), 'text/plain');
                        
                        $this->get('mailer')->send($message);
                        
                        $token = new UsernamePasswordToken($user->getUser(), $user->getPassword(), "main", $user->getUser()->getRoles());
                        $this->get("security.context")->setToken($token);
                        
                        $event = new InteractiveLoginEvent($request, $token);
                        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                    }
                    
                    return array(
                        "success" => true,
                        'preference' => $preference
                    );
                }
            }
            
            return array(
                "success" => false,
                "form" => $signinForm->createView(),
                "signinAllowed" => true
            );
        } else {
            throw $this->createAccessDeniedException("Access forbiden due to signin desallowed");
        }
    }

    /**
     * Forgot action
     * 
     * This action provide the
     * default page for the forgotten
     * password page.
     * 
     * @template
     */
    public function forgotAction(Request $request)
    {
        $preference = $this->getPreference();
        $reaction = $preference->getConfiguration()->getForgotPasswordReaction();
        
        if ($reaction == Configuration::PASSWORD_FORGOT_NOREACT) {
            return array("config" => $preference->getConfiguration());
        } else if ($reaction == Configuration::PASSWORD_FORGOT_AUTOMAIL) {
            
            $formData = array();
            $formArray = $this->createFormBuilder($formData)
                ->add("email", "text", array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email'
                )
            ))
                ->add("send", "submit", array(
                'label' => 'Send request',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
                ->getForm();
            
            if ($request->getMethod() == "POST") {
                $formArray->handleRequest($request);
                
                $data = $formArray->getData();
                
                $userRepository = $this->getDoctrine()->getRepository("CscfaSecurityBundle:User");
                $user = $userRepository->findOneByEmail($data["email"]);
                
                if ($user && $user instanceof User) {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $textPassword = substr(str_shuffle($chars), 0, 10);
                    
                    $password = $encoder->encodePassword($textPassword, $user->getSalt());
                    $user->setPassword($password);
                    
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush();
                    
                    $message = \Swift_Message::newInstance()->setSubject('CSManager : password updated')
                        ->setFrom($preference->getEmailSender())
                        ->setTo($user->getEmail())
                        ->setBody($this->renderView('CscfaCSManagerSecurityBundle:mail:forgot.html.twig', array(
                        'plainPassword' => $textPassword,
                        'preference' => $preference
                    )), 'text/html')
                        ->addPart($this->renderView('CscfaCSManagerSecurityBundle:mail:forgot.txt.twig', array(
                        'plainPassword' => $textPassword
                    )), 'text/plain');
                    
                    $this->get('mailer')->send($message);
                    
                    $this->addFlash("success", "Y'our password is succefully updated. Please, check you'r mail box");
                } else {
                    $formArray->get("email")->addError(new FormError("The given email doesn't exist"));
                }
            }
            
            return array(
                "config" => $preference->getConfiguration(),
                "form" => $formArray->createView()
            );
        } else {
            $logger = $this->get('logger');
            $logger->error('An error occurred : SecurityController forgotAction. The reaction provided by the preference cannot be recognized by the system.');
            
            throw new \Exception("Reaction error. See logs.", 500, null);
        }
    }

    /**
     * Login check action.
     * 
     * This action provide 
     * the login check logic.
     * 
     * Note that the framework
     * handle this action.
     */
    public function loginCheckAction()
    {}

    /**
     * Logout action.
     * 
     * This action provide 
     * the logout logic.
     * 
     * Note that the framework
     * handle this action.
     */
    public function logoutAction()
    {}

    /**
     * Get preference
     * 
     * Get the current preference
     * 
     * @throws \Exception - if any database error occured
     * @return Preference - The current preference
     */
    protected function getPreference()
    {
        $preferenceRepository = $this->getDoctrine()->getRepository("CscfaCSManagerConfigBundle:Preference");
        $preference = $preferenceRepository->getCurrentOrNull();
        
        if ($preference === null) {
            $logger = $this->get('logger');
            $logger->error('An error occurred : SecurityController getPreference. No preference find into the database or the repository have one problem to retreiving the instance.');
            
            throw new \Exception("Database error for csmanager preference storage. See logs.", 500, null);
        } else {
            return $preference;
        }
    }
}
