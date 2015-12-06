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
 * @package  CscfaCSManagerConfigBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preference
 *
 * The base Preference entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ConfigBundle\Entity\Repository\PreferenceRepository")
 * @ORM\Table(name="csmanager_config_preference")
 * @ORM\HasLifecycleCallbacks
 */
class Preference
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_config_id", options={"comment":"Preference id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_config_created", options={"comment":"Preference date of creation"}) 
     */
    protected $created;

    /** 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_config_updated", options={"comment":"Preference date of last update"}) 
     */
    protected $updated;

    /** 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The Preference deletion state"}, nullable=false, name="csmanager_config_deleted") 
     */
    protected $deleted;

    /**
     * @ORM\ManyToOne(targetEntity="Configuration")
     * @ORM\JoinColumn(name="csmanager_config_configuration_id", referencedColumnName="csmanager_config_id")
     */
    protected $configuration;

    /** 
     * @ORM\Column(type="string", options={"comment":"The Preference email sender"}, nullable=true, name="csmanager_config_email_sender") 
     */
    protected $emailSender;

    /**
     * @ORM\Column(type="string", options={"comment":"The Preference site base url"}, nullable=true, name="csmanager_config_site_base_url")
     */
    protected $siteBaseUrl;
    
    /**
     * Preference constructor
     * 
     * Setup the entity
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->deleted = false;
        $this->updated = null;
    }

    /**
     * Get id
     * 
     * Return the entity id.
     * 
     * @return string - return the entity id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get created
     * 
     * Return the creation date
     * of the entity.
     * 
     * @return \DateTime - The creation date
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get updated
     * 
     * Return the update date
     * of the entity.
     * 
     * @return \DateTime | null - The entity update date or null if never updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get deleted
     * 
     * Return the deletion state
     * of the entity.
     * 
     * @return boolean - the entity deletion state
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get configuration
     * 
     * Return the current used configuration
     * 
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Get email sender
     * 
     * Return the current email sender
     * 
     * @return string - the email sender
     */
    public function getEmailSender()
    {
        return $this->emailSender;
    }

    /**
     * Get site base url
     * 
     * Return the current base url of the site
     * 
     * @return string - the site base url
     */
    public function getSiteBaseUrl()
    {
        return $this->siteBaseUrl;
    }
       
    /**
     * Set updated
     * 
     * Setup the updated date
     * to the current date.
     * 
     * @return Preference - the current entity
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();
        return $this;
    }

    /**
     * Set deleted
     * 
     * Set the deleted state of
     * the entity. If the given
     * state is not a boolean,
     * the variable is cast to 
     * boolean.
     * 
     * @param mixed $deleted - the state of the deletion
     * 
     * @return Preference - the current entity
     */
    public function setDeleted($deleted)
    {
        if (! is_bool($deleted)) {
            $deleted = boolval($deleted);
        }
        
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * Set configuration
     * 
     * Set the current used configuration
     * 
     * @param Configuration $configuration - the configuration entity
     * 
     * @return Preference - the current entity
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Set email sender
     * 
     * Set the email sender
     * 
     * @param string $emailSender - the email
     * 
     * @return Preference - the current entity
     */
    public function setEmailSender($emailSender)
    {
        $this->emailSender = $emailSender;
        return $this;
    }

    /**
     * Set site base url
     * 
     * Set the current site base url
     * 
     * @param string $siteBaseUrl - the site base url
     * 
     * @return Preference - the current site base url
     */
    public function setSiteBaseUrl($siteBaseUrl)
    {
        $this->siteBaseUrl = $siteBaseUrl;
        return $this;
    }
    
    /**
     * Update
     * 
     * PreUpdate the entity to
     * store the update date
     * 
     * @ORM\PreUpdate
     * 
     * @return null
     */
    protected function update(){
        $this->setUpdated();
    }
}
