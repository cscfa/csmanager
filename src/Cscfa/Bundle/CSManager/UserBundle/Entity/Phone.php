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
 * @package  CscfaCSManagerUserBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * The phone entity for the
 * Cscfaproject manager
 * 
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UserBundle\Entity\Repository\PhoneRepository")
 * @ORM\Table(name="csmanager_user_phone")
 */
class Phone
{
    
    /**
     * The id field
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
     *      type="guid", name="phone_id", unique=true, nullable=false, options={"comment":"phone identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * The type
     * 
     * The phone type
     * 
     * @var Type
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="csmanager_phone_type_id", referencedColumnName="type_id")
     */
    protected $type;

    /**
     * The number
     * 
     * The phone number
     * 
     * @var string
     * @ORM\Column(type="string", length=255, options={"comment":"The phone number"}, nullable=false, name="csmanager_phone_number")
     */
    protected $number;

    /**
     * Get id
     * 
     * Return the entity UUID
     * 
     * @return string - the entity UUID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get type
     * 
     * Return the phone type
     * 
     * @return Type - the phone type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * 
     * Set the phone type
     * 
     * @param Type $type - the phone type
     * @return Phone - the current instance
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get number
     * 
     * Return the phone number
     * 
     * @return string - the phone number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     * 
     * Set the phone number
     * 
     * @param string $number - the phone number
     * @return Phone - the current instance
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }
 
}
