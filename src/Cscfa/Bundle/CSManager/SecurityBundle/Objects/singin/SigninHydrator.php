<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Objects
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin;

use Symfony\Component\Form\Form;
use Cscfa\Bundle\SecurityBundle\Util\Manager\UserManager;
use Cscfa\Bundle\SecurityBundle\Util\Builder\UserBuilder;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormError;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Person;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Phone;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Adress;
use Cscfa\Bundle\CSManager\ConfigBundle\Entity\Preference;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * SigninHydrator class.
 *
 * The SigninHydrator provide
 * method to manage the sgnin
 * form hydratation.
 *
 * @category Objects
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class SigninHydrator
{
    
    /**
     * SigninHydrator attribute
     * 
     * This attribute register
     * the translator service.
     * 
     * @var TranslatorInterface
     */
    protected $translator;
    
    /**
     * SigninHydrator attribute
     * 
     * This attribute indicate
     * the translation domain
     * to be used.
     * 
     * @var string
     */
    protected $domain;
    
    /**
     * SigninHydrator constructor
     * 
     * Default SigninHydrator constructor.
     * 
     * @param TranslatorInterface $translator The translator service
     * @param string              $domain     The translation domain
     */
    public function __construct(TranslatorInterface $translator, $domain)
    {
        $this->translator = $translator;
        $this->domain = $domain;
    }

    /**
     * Get SigninObject
     * 
     * Return the SigninObject
     * the contain the form data
     * 
     * @param Form $form - the parent form
     * 
     * @return SignInObject
     */
    protected function getSigninObject(Form $form)
    {
        return $form->getData();
    }

    /**
     * Hydrate address
     * 
     * Hydrate the person instance
     * with the data informations
     * about address
     * 
     * @param ObjectManager $manager - the database manager
     * @param Form          $form    - the form containing the datas
     * @param Person        $person  - the person passed by reference
     */
    public function hydrateAddress(ObjectManager &$manager, Form $form, Person &$person)
    {
        $data = $this->getSigninObject($form);
        
        $address = new Adress();
        $address->setReferer($data->getReferer())
            ->setAdress($data->getAdress())
            ->setComplement($data->getComplement())
            ->setTown($data->getTown())
            ->setPostalCode($data->getPostalCode())
            ->setCountry($data->getCountry());
        
        $manager->persist($address);
        $person->getAdresses()->add($address);
    }

    /**
     * Hydrate company
     * 
     * Hydrate the person instance
     * with the data informations
     * about company
     * 
     * @param ObjectManager $manager - the database manager
     * @param Form          $form    - the form containing the datas
     * @param Person        $person  - the person passed by reference
     */
    public function hydrateCompany(ObjectManager &$manager, Form $form, Person &$person)
    {
        $data = $this->getSigninObject($form);
        
        $person->setCompany($data->getCompany())
            ->setService($data->getService())
            ->setJob($data->getJob());
        
        $address = new Adress();
        $address->setReferer($data->getCompanyReferer())
            ->setAdress($data->getCompanyAdress())
            ->setComplement($data->getCompanyComplement())
            ->setTown($data->getCompanyTown())
            ->setPostalCode($data->getCompanyPostalCode())
            ->setCountry($data->getCompanyCountry());
        
        $manager->persist($address);
        $person->setCompanyAdress($address);
    }

    /**
     * Hydrate yourself
     * 
     * Hydrate the person instance
     * with the data informations
     * about person
     * 
     * @param Form   $form   - the form containing the datas
     * @param Person $person - the person passed by reference
     * 
     * @return void
     */
    public function hydrateYourself(Form $form, Person &$person)
    {
        $data = $this->getSigninObject($form);
        
        $person->setFirstName($data->getFirstName());
        $person->setLastName($data->getLastName());
        $person->setSex(boolval($data->getSex()));
        $person->setBirthday($data->getBirthday());
        $person->setBiography(empty($data->getBiography()) ? null : $data->getBiography());
    }

    /**
     * Hydrate phone
     * 
     * Hydrate and persist a
     * Person instance with
     * a new Phone instance
     * 
     * @param ObjectManager $manager     - the database manager
     * @param Form          $form        - the form containing the datas
     * @param Person        $person      - the person passed by reference
     * 
     * @return void
     */
    public function hydratePhone(ObjectManager &$manager, Form $form, Person &$person)
    {
        $data = $this->getSigninObject($form);
        
        $phone = new Phone();
        $phone->setType($data->getPhoneType())
            ->setNumber($data->getPhoneNumber());
        $manager->persist($phone);
        
        $person->getPhones()->add($phone);
    }

    /**
     * Hydrate base
     * 
     * Hydrate and persist an
     * User instance. Manage the
     * errors if exists.
     * 
     * This method will return the
     * error state.
     * 
     * Note the $user parameter can
     * be used to retreive the User
     * instance.
     * 
     * @param ObjectManager $manager     - the database manager
     * @param UserManager   $userManager - the user manager service
     * @param Form          $form        - the form containing the datas
     * @param mixed         $user        - the user passed by reference
     * @param Preference    $preference  - the current application preference
     * 
     * @return boolean - the error state
     */
    public function hydrateBase(ObjectManager &$manager, UserManager $userManager, Form &$form, &$user, Preference $preference)
    {
        $repository = $manager->getRepository("Cscfa\Bundle\SecurityBundle\Entity\Role");
        $roleUser = $repository->findOneByName("ROLE_USER");
        
        if (! $roleUser) {
            throw new \Exception("Cannot find base role. Will create connection issue.", 404);
        }
        
        $data = $this->getSigninObject($form);
        $user = $userManager->getNewInstance();
        $errors = false;
        
        $user->removeLastError();
        $user->addRole($roleUser);
        
        if (! $preference->getConfiguration()->getSignInVerifyEmail()) {
            $user->setEnabled(true);
        }
        
        $user->removeLastError();
        $user->setUsername($data->getPseudo());
        if ($user->getLastError() !== UserBuilder::NO_ERROR) {
            $errors = true;
            if ($user->getLastError() == UserBuilder::INVALID_USERNAME) {
                $form->get("pseudo")->addError(new FormError($this->translator->trans("pseudo.invalid", [], $this->domain)));
            } else if ($user->getLastError() == UserBuilder::DUPLICATE_USERNAME) {
                $form->get("pseudo")->addError(new FormError($this->translator->trans("pseudo.duplicate", [], $this->domain)));
            } else {
                $form->get("pseudo")->addError(new FormError($this->translator->trans("pseudo.undefined", [], $this->domain)));
            }
        }
        
        $user->removeLastError();
        $user->setEmail($data->getEmail());
        if ($user->getLastError() !== UserBuilder::NO_ERROR) {
            $errors = true;
            if ($user->getLastError() == UserBuilder::INVALID_EMAIL) {
                $form->get("email")->addError(new FormError($this->translator->trans("email.invalid", [], $this->domain)));
            } else if ($user->getLastError() == UserBuilder::DUPLICATE_EMAIL) {
                $form->get("email")->addError(new FormError($this->translator->trans("email.duplicate", [], $this->domain)));
            } else {
                $form->get("email")->addError(new FormError($this->translator->trans("email.undefined", [], $this->domain)));
            }
        }
        
        $user->removeLastError();
        $user->setPassword($data->getPassword());
        if ($user->getLastError() !== UserBuilder::NO_ERROR) {
            $errors = true;
            if ($user->getLastError() == UserBuilder::EMPTY_PASSWORD) {
                $form->get("password")->addError(new FormError($this->translator->trans("password.empty", [], $this->domain)));
            } else {
                $form->get("password")->addError(new FormError($this->translator->trans("password.undefined", [], $this->domain)));
            }
        }
        
        $manager->persist($user->getUser());
        
        return $errors;
    }
}
