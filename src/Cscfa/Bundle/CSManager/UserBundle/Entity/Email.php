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

/**
 * Email.
 *
 * The email entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UserBundle\Entity\Repository\EmailRepository")
 * @ORM\Table(name="csmanager_user_email")
 */
class Email
{
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
     *      type="guid", name="email_id", unique=true, nullable=false, options={"comment":"email identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $emailId;

    /**
     * The type.
     *
     * The email type
     *
     * @var Type
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="csmanager_email_type_id", referencedColumnName="type_id")
     */
    protected $type;

    /**
     * The address.
     *
     * The email address
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The email adress"},
     *      nullable=false,
     *      name="csmanager_email_adress"
     * )
     */
    protected $adress;

    /**
     * Get id.
     *
     * Return the email UUID
     *
     * @return string
     */
    public function getId()
    {
        return $this->emailId;
    }

    /**
     * Get type.
     *
     * Return the email type
     *
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * Set the email type
     *
     * @param Type $type - the email type
     *
     * @return Email - the current instance
     */
    public function setType(Type $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get adress.
     *
     * Return the email address
     *
     * @return string - the email address
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set address.
     *
     * Set the email address
     *
     * @param string $adress - the email address
     *
     * @return Email - the current instance
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }
}
