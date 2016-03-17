<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Entity
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Person.
 *
 * The person entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UserBundle\Entity\Repository\PersonRepository")
 * @ORM\Table(name="csmanager_user_person",
 *      indexes={@ORM\Index(name="cs_manager_userid_indx", columns={"csmanager_person_user_id"})}
 *      )
 */
class Person
{
    /**
     * Sex type.
     *
     * The male sex type
     *
     * @var bool
     */
    const SEX_MALE = true;
    /**
     * Sex type.
     *
     * The female sex type
     *
     * @var bool
     */
    const SEX_FEMALE = false;

    /**
     * The id field.
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into user_id field into GUID
     * format, is unique and can't be null.
     *
     * @ORM\Column(
     *      type="guid", name="person_id", unique=true, nullable=false, options={"comment":"person identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $personId;

    /**
     * The user.
     *
     * The user refering to an
     * user entity that store the
     * person login informations
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\SecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="csmanager_person_user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * The emails.
     *
     * The emails refering to a
     * set of emails that can be
     * used to contact the person
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Email")
     * @ORM\JoinTable(name="tk_csmanager_person_join_email",
     *      joinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="email_id", referencedColumnName="email_id")}
     *      )
     */
    protected $emails;

    /**
     * The firstname.
     *
     * The person firstname
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The person firstname"},
     *      nullable=true,
     *      name="csmanager_person_firstName"
     * )
     */
    protected $firstName;
    /**
     * The lastname.
     *
     * The person lastname
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The person lastname"},
     *      nullable=true,
     *      name="csmanager_person_lastName"
     * )
     */
    protected $lastName;
    /**
     * The sex.
     *
     * The person sex, refering
     * to Person class constant
     *
     * @var bool
     * @ORM\Column(
     *      type="boolean",
     *      options={"comment":"The person sex"},
     *      nullable=true,
     *      name="csmanager_person_sex"
     * )
     */
    protected $sex;
    /**
     * The birthday.
     *
     * The person birthday store
     * as \DateTime
     *
     * @var \DateTime
     * @ORM\Column(
     *      type="datetime",
     *      nullable=true,
     *      name="csmanager_person_birthday",
     *      options={"comment":"The person birthday"}
     * )
     */
    protected $birthday;

    /**
     * The addresses.
     *
     * The addresses refering to a
     * set of address that can be
     * used to contact the person
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Adress")
     * @ORM\JoinTable(name="tk_csmanager_person_join_adress",
     *      joinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="adress_id", referencedColumnName="adress_id")}
     *      )
     */
    protected $adresses;

    /**
     * The phones.
     *
     * The phones refering to a
     * set of phones number that
     * can be used to contact
     * the person
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Phone")
     * @ORM\JoinTable(name="tk_csmanager_person_join_phone",
     *      joinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="person_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="phone_id", referencedColumnName="phone_id")}
     *      )
     */
    protected $phones;

    /**
     * The company.
     *
     * The person company
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The person company"},
     *      nullable=true,
     *      name="csmanager_person_company"
     * )
     */
    protected $company;
    /**
     * The company adress.
     *
     * The company address refering
     * to an Adress entity that can
     * be used to contact the person
     * company
     *
     * @var Adress
     * @ORM\ManyToOne(targetEntity="Adress")
     * @ORM\JoinColumn(
     *      name="csmanager_person_company_adress", referencedColumnName="adress_id"
     * )
     */
    protected $companyAdress;
    /**
     * The service.
     *
     * The service where work the
     * person into the company
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The person service"},
     *      nullable=true,
     *      name="csmanager_person_service"
     * )
     */
    protected $service;
    /**
     * The job.
     *
     * The job name of the person
     * into the company
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The person job"},
     *      nullable=true,
     *      name="csmanager_person_job"
     * )
     */
    protected $job;
    /**
     * The biography.
     *
     * The person biography
     *
     * @var string
     * @ORM\Column(
     *      type="text",
     *      options={"comment":"The person biography"},
     *      nullable=true,
     *      name="csmanager_person_biography"
     * )
     */
    protected $biography;

    /**
     * Get id.
     *
     * Return the entity id
     *
     * @return string - the entity UUID
     */
    public function getId()
    {
        return $this->personId;
    }

    /**
     * Get user.
     *
     * Return the entity joined
     * User entity
     *
     * @return User - the user instance
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * Set the entity joined
     * User entity
     *
     * @param User $user - the usern entity to join
     *
     * @return Person - the current entity
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get emails.
     *
     * Return the collection of
     * the entity joined emails
     * entities
     *
     * @return ArrayCollection - the collection of emails
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Set emails.
     *
     * Set the joined emails
     * collection
     *
     * @param ArrayCollection $emails - the joined email collection
     *
     * @return Person - the current entity
     */
    public function setEmails(ArrayCollection $emails)
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * Get first name.
     *
     * Return the person
     * first name
     *
     * @return string - the firstname
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set first name.
     *
     * Set the person
     * first name
     *
     * @param string $firstName - the firstname
     *
     * @return Person - the current entity
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get last name.
     *
     * Return the person
     * last name
     *
     * @return string - the lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set last name.
     *
     * Set the person
     * last name
     *
     * @param string $lastName - the lastName
     *
     * @return Person - the current entity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get sex.
     *
     * Return the person
     * sex
     *
     * @return bool - the sex
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set sex.
     *
     * Set the person
     * sex
     *
     * @param bool $sex - the sex
     *
     * @return Person - the current entity
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get birthday.
     *
     * Return the person
     * birthday
     *
     * @return \Date - the birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set birthday.
     *
     * Set the person
     * birthday
     *
     * @param \Date $birthday - the birthday
     *
     * @return Person - the current entity
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get joined address.
     *
     * Return the entity joined
     * address entities
     *
     * @return ArrayCollection - the address joined entities
     */
    public function getAdresses()
    {
        return $this->adresses;
    }

    /**
     * Set joined address.
     *
     * Set the entity joined
     * address entities
     *
     * @param ArrayCollection $adresses - the address joined entities
     *
     * @return Person - the current entity
     */
    public function setAdresses(ArrayCollection $adresses)
    {
        $this->adresses = $adresses;

        return $this;
    }

    /**
     * Get joined phones.
     *
     * Return the entity joined
     * phones entities
     *
     * @return ArrayCollection - the phones joined entities
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set joined phones.
     *
     * Set the entity joined
     * phones entities
     *
     * @param ArrayCollection $phones - the phones joined entities
     *
     * @return Person - the current entity
     */
    public function setPhones(ArrayCollection $phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get company.
     *
     * Return the person
     * company name
     *
     * @return string - the company name
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company.
     *
     * Set the person
     * company name
     *
     * @param string $company - the company name
     *
     * @return Person - the current entity
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company address.
     *
     * Return the person
     * company address
     *
     * @return Adress - the person company address
     */
    public function getCompanyAdress()
    {
        return $this->companyAdress;
    }

    /**
     * Set company address.
     *
     * Set the person company
     * address
     *
     * @param Adress $companyAdress - the person company address
     *
     * @return Person - the current entity
     */
    public function setCompanyAdress(Adress $companyAdress)
    {
        $this->companyAdress = $companyAdress;

        return $this;
    }

    /**
     * Get service.
     *
     * Return the person
     * service
     *
     * @return string - the person service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set service.
     *
     * Set the person
     * service
     *
     * @param string $service - the person service
     *
     * @return Person - the current entity
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get job.
     *
     * Return the person
     * job
     *
     * @return string - the person job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job.
     *
     * Set the person
     * job
     *
     * @param string $job - the person job
     *
     * @return Person - the current entity
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get biography.
     *
     * Return the person
     * biography
     *
     * @return string - the person biography
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set biography.
     *
     * Set the person
     * biography
     *
     * @param string $biography - the person biography
     *
     * @return Person - the current entity
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }
}
