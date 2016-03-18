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
 * Adress.
 *
 * The adress entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UserBundle\Entity\Repository\AdressRepository")
 * @ORM\Table(name="csmanager_user_adress")
 */
class Adress
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
     *      type="guid", name="adress_id", unique=true, nullable=false, options={"comment":"adress identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $adressId;

    /**
     * The referer.
     *
     * The adress referer name
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The adress receiver referer"},
     *      nullable=false,
     *      name="csmanager_adress_referer"
     * )
     */
    protected $referer;

    /**
     * The adress.
     *
     * The adress text
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The adress"},
     *      nullable=false,
     *      name="csmanager_adress_text"
     * )
     */
    protected $adress;

    /**
     * The complement.
     *
     * The adress complement
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The adress complement"},
     *      nullable=true,
     *      name="csmanager_adress_complement"
     * )
     */
    protected $complement;

    /**
     * The town.
     *
     * The address town
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The adress town"},
     *      nullable=false,
     *      name="csmanager_adress_town"
     * )
     */
    protected $town;

    /**
     * The postal code.
     *
     * The address postal code
     *
     * @var int
     * @ORM\Column(
     *      type="integer",
     *      options={"comment":"The adress postal code"},
     *      nullable=false,
     *      name="csmanager_adress_postal_code"
     * )
     */
    protected $postalCode;

    /**
     * The country.
     *
     * The address country
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The adress country"},
     *      nullable=false,
     *      name="csmanager_adress_country"
     * )
     */
    protected $country;

    /**
     * Get id.
     *
     * Return the entity id
     *
     * @return string - the entity UUID
     */
    public function getId()
    {
        return $this->adressId;
    }

    /**
     * Get referer.
     *
     * Return the adress referer
     * name
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
     * Set the adress referer
     * name
     *
     * @param string $referer - the referer name
     *
     * @return Adress - the current instance
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get adress.
     *
     * Return the adress
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
     * set the adress
     *
     * @param string $adress - the adress
     *
     * @return Adress - the current instance
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get complement.
     *
     * Return the adress complement
     *
     * @return string - the adress complement
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set complement.
     *
     * Set the adress complement
     *
     * @param string $complement - the adress complement
     *
     * @return Adress - the current instance
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get town.
     *
     * Return the adress town
     *
     * @return string - the town name
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set town.
     *
     * Set the adress town name
     *
     * @param string $town - the address town name
     *
     * @return Adress - the current instance
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get postal code.
     *
     * Return the address postal
     * code
     *
     * @return int - the address postal code
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set postal code.
     *
     * Set the address postal code
     *
     * @param int $postalCode - the address postal code
     *
     * @return Adress - the current instance
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get country.
     *
     * Return the address country name
     *
     * @return string - the address country name
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country.
     *
     * Set the adress country name
     *
     * @param string $country - the country name
     *
     * @return Adress - the current instance
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}
