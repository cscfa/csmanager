<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin;

use Symfony\Component\Validator\Constraints as Assert;
use Cscfa\Bundle\CSManager\SecurityBundle\Validator\Constraints as CSSecurityAssert;
use Cscfa\Bundle\CSManager\UserBundle\Entity\Type;

/**
 * SignInObject class.
 *
 * The SignInObject store
 * the base signin logic.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class SignInObject
{
    /**
     * Pseudo.
     *
     * The registering user
     * pseudo.
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex("/^[a-zA-Z][a-zA-Z0-9_-]+$/")
     */
    protected $pseudo;
    /**
     * Email.
     *
     * The registering user
     * email
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email(checkMX = true)
     */
    protected $email;
    /**
     * Password.
     *
     * The registering user
     * password
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    protected $password;

    /**
     * Phone type.
     *
     * The registering user
     * phone type
     *
     * @var Type
     */
    protected $phoneType;
    /**
     * Phone number.
     *
     * The registering user
     * phone number
     *
     * @var string
     * @CSSecurityAssert\Phone(groups={"Phone"})
     */
    protected $phoneNumber;

    /**
     * First name.
     *
     * The registering user
     * first name
     *
     * @var string
     * @Assert\NotBlank(groups={"Yourself"})
     */
    protected $firstName;
    /**
     * Last name.
     *
     * The registering user
     * first name
     *
     * @var string
     * @Assert\NotBlank(groups={"Yourself"})
     */
    protected $lastName;
    /**
     * Sex.
     *
     * The registering user
     * sex
     *
     * @var bool
     */
    protected $sex;
    /**
     * Birthday.
     *
     * The registering user
     * birthday
     *
     * @var \Date
     * @Assert\Date()
     */
    protected $birthday;
    /**
     * Biography.
     *
     * The registering user
     * biography
     *
     * @var string
     */
    protected $biography;

    /**
     * Company.
     *
     * The registering user
     * company
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $company;
    /**
     * Company referer.
     *
     * The registering user
     * company referer
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $companyReferer;
    /**
     * Company address.
     *
     * The registering user
     * company address
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $companyAdress;
    /**
     * Company address complement.
     *
     * The registering user
     * company address complement
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $companyComplement;
    /**
     * Company town.
     *
     * The registering user
     * company town
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $companyTown;
    /**
     * Company postal code.
     *
     * The registering user
     * company postal code
     *
     * @var string
     * @Assert\Regex("/^.{0,10}$/", groups={"Company"})
     */
    protected $companyPostalCode;
    /**
     * Company country.
     *
     * The registering user
     * company country
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $companyCountry;
    /**
     * Service.
     *
     * The registering user
     * service
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $service;
    /**
     * Job.
     *
     * The registering user
     * job
     *
     * @var string
     * @Assert\NotBlank(groups={"Company"})
     */
    protected $job;

    /**
     * Referer.
     *
     * The registering user
     * referer
     *
     * @var string
     * @Assert\NotBlank(groups={"Address"})
     */
    protected $referer;
    /**
     * Address.
     *
     * The registering user
     * address
     *
     * @var string
     * @Assert\NotBlank(groups={"Address"})
     */
    protected $adress;
    /**
     * Address complement.
     *
     * The registering user
     * address complement
     *
     * @var string
     * @Assert\NotBlank(groups={"Address"})
     */
    protected $complement;
    /**
     * Town.
     *
     * The registering user
     * town
     *
     * @var string
     * @Assert\NotBlank(groups={"Address"})
     */
    protected $town;
    /**
     * Postal code.
     *
     * The registering user
     * postal code
     *
     * @var string
     * @Assert\Regex("/^.{0,10}$/", groups={"Address"})
     */
    protected $postalCode;
    /**
     * Country.
     *
     * The registering user
     * country
     *
     * @var string
     * @Assert\NotBlank(groups={"Address"})
     */
    protected $country;

    /**
     * Get pseudo.
     *
     * Return the user registering
     * pseudo.
     *
     * @return string - the pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set pseudo.
     *
     * Set the user registering
     * pseudo
     *
     * @param string $pseudo - the pseudo
     *
     * @return SignInObject - the current instance
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get Email.
     *
     * Return the user registering
     * email
     *
     * @return string - the email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * Set the user registering
     * email
     *
     * @param string $email - the email
     *
     * @return SignInObject - the current instance
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get password.
     *
     * Return the user registering
     * password
     *
     * @return string - the password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * Set the user registering
     * password
     *
     * @param string $password - the password
     *
     * @return SignInObject - the current instance
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get phoneType.
     *
     * Return the user registering
     * phoneType
     *
     * @return Type - the phoneType
     */
    public function getPhoneType()
    {
        return $this->phoneType;
    }

    /**
     * Set phoneType.
     *
     * Set the user registering
     * phoneType
     *
     * @param Type $phoneType - the phoneType
     *
     * @return SignInObject - the current instance
     */
    public function setPhoneType($phoneType)
    {
        $this->phoneType = $phoneType;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * Return the user registering
     * phoneNumber
     *
     * @return string - the phoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set phoneNumber.
     *
     * Set the user registering
     * phoneNumber
     *
     * @param string $phoneNumber - the phoneNumber
     *
     * @return SignInObject - the current instance
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get firstName.
     *
     * Return the user registering
     * firstName
     *
     * @return string - the firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName.
     *
     * Set the user registering
     * firstName
     *
     * @param string $firstName - the firstName
     *
     * @return SignInObject - the current instance
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * Return the user registering
     * lastName
     *
     * @return string - the lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName.
     *
     * Set the user registering
     * lastName
     *
     * @param string $lastName - the lastName
     *
     * @return SignInObject - the current instance
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get sex.
     *
     * Return the user registering
     * sex
     *
     * @return string - the sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set sex.
     *
     * Set the user registering
     * sex
     *
     * @param string $sex - the sex
     *
     * @return SignInObject - the current instance
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get birthday.
     *
     * Return the user registering
     * birthday
     *
     * @return string - the birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set birthday.
     *
     * Set the user registering
     * birthday
     *
     * @param string $birthday - the birthday
     *
     * @return SignInObject - the current instance
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get biography.
     *
     * Return the user registering
     * biography
     *
     * @return string - the biography
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set biography.
     *
     * Set the user registering
     * biography
     *
     * @param string $biography - the biography
     *
     * @return SignInObject - the current instance
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get company.
     *
     * Return the user registering
     * company
     *
     * @return string - the company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company.
     *
     * Set the user registering
     * company
     *
     * @param string $company - the company
     *
     * @return SignInObject - the current instance
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company referer.
     *
     * Return the user registering
     * company referer
     *
     * @return string - the company referer
     */
    public function getCompanyReferer()
    {
        return $this->companyReferer;
    }

    /**
     * Set company referer.
     *
     * Set the user registering
     * company referer
     *
     * @param string $companyReferer - the company referer
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyReferer($companyReferer)
    {
        $this->companyReferer = $companyReferer;

        return $this;
    }

    /**
     * Get company address.
     *
     * Return the user registering
     * company address
     *
     * @return string - the company address
     */
    public function getCompanyAdress()
    {
        return $this->companyAdress;
    }

    /**
     * Set company address.
     *
     * Set the user registering
     * company address
     *
     * @param string $companyAdress - the company address
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyAdress($companyAdress)
    {
        $this->companyAdress = $companyAdress;

        return $this;
    }

    /**
     * Get company address complement.
     *
     * Return the user registering
     * company address complement
     *
     * @return string - the company address complement
     */
    public function getCompanyComplement()
    {
        return $this->companyComplement;
    }

    /**
     * Set company address complement.
     *
     * Set the user registering
     * company address complement
     *
     * @param string $companyComplement - the company address complement
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyComplement($companyComplement)
    {
        $this->companyComplement = $companyComplement;

        return $this;
    }

    /**
     * Get company town.
     *
     * Return the user registering
     * company town
     *
     * @return string - the company town
     */
    public function getCompanyTown()
    {
        return $this->companyTown;
    }

    /**
     * Set company town.
     *
     * Set the user registering
     * company town
     *
     * @param string $companyTown - the company town
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyTown($companyTown)
    {
        $this->companyTown = $companyTown;

        return $this;
    }

    /**
     * Get company postal code.
     *
     * Return the user registering
     * company postal code
     *
     * @return string - the company postal code
     */
    public function getCompanyPostalCode()
    {
        return $this->companyPostalCode;
    }

    /**
     * Set company postal code.
     *
     * Set the user registering
     * company postal code
     *
     * @param string $companyPostalCode - the company postal code
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyPostalCode($companyPostalCode)
    {
        $this->companyPostalCode = $companyPostalCode;

        return $this;
    }

    /**
     * Get company country.
     *
     * Return the user registering
     * company country
     *
     * @return string - the company country
     */
    public function getCompanyCountry()
    {
        return $this->companyCountry;
    }

    /**
     * Set company country.
     *
     * Set the user registering
     * company country
     *
     * @param string $companyCountry - the company country
     *
     * @return SignInObject - the current instance
     */
    public function setCompanyCountry($companyCountry)
    {
        $this->companyCountry = $companyCountry;

        return $this;
    }

    /**
     * Get service.
     *
     * Return the user registering
     * service
     *
     * @return string - the service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set service.
     *
     * Set the user registering
     * service
     *
     * @param string $service - the service
     *
     * @return SignInObject - the current instance
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get job.
     *
     * Return the user registering
     * job
     *
     * @return string - the job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job.
     *
     * Set the user registering
     * job
     *
     * @param string $job - the job
     *
     * @return SignInObject - the current instance
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get referer.
     *
     * Return the user registering
     * referer
     *
     * @return string - the referer
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set referer.
     *
     * Set the user registering
     * referer
     *
     * @param string $referer - the referer
     *
     * @return SignInObject - the current instance
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get adress.
     *
     * Return the user registering
     * adress
     *
     * @return string - the adress
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set adress.
     *
     * Set the user registering
     * adress
     *
     * @param string $adress - the adress
     *
     * @return SignInObject - the current instance
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get complement.
     *
     * Return the user registering
     * complement
     *
     * @return string - the complement
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set complement.
     *
     * Set the user registering
     * complement
     *
     * @param string $complement - the complement
     *
     * @return SignInObject - the current instance
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get town.
     *
     * Return the user registering
     * town
     *
     * @return string - the town
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set town.
     *
     * Set the user registering
     * town
     *
     * @param string $town - the town
     *
     * @return SignInObject - the current instance
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * Return the user registering
     * postalCode
     *
     * @return string - the postalCode
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set postalCode.
     *
     * Set the user registering
     * postalCode
     *
     * @param string $postalCode - the postalCode
     *
     * @return SignInObject - the current instance
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get country.
     *
     * Return the user registering
     * country
     *
     * @return string - the country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country.
     *
     * Set the user registering
     * country
     *
     * @param string $country - the country
     *
     * @return SignInObject - the current instance
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getGroups()
    {
        $result = array('Default');

        if (!empty($this->getPhoneNumber())) {
            $result[] = 'Phone';
        }

        if (!empty($this->getFirstName()) ||
            !empty($this->getLastName())) {
            $result[] = 'Yourself';
        }

        if (!empty($this->getCompany()) ||
                !empty($this->getCompanyAdress()) ||
                !empty($this->getCompanyComplement()) ||
                !empty($this->getCompanyCountry()) ||
                !empty($this->getCompanyPostalCode()) ||
                !empty($this->getCompanyReferer()) ||
                !empty($this->getCompanyTown())) {
            $result[] = 'Company';
        }

        if (!empty($this->getAdress()) ||
                    !empty($this->getComplement()) ||
                    !empty($this->getCountry()) ||
                    !empty($this->getPostalCode()) ||
                    !empty($this->getReferer()) ||
                    !empty($this->getTown())) {
            $result[] = 'Address';
        }

        return $result;
    }
}
