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
 * Configuration
 *
 * The base Configuration entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ConfigBundle\Entity\Repository\ConfigRepository")
 * @ORM\Table(name="csmanager_config_configuration",
 *      indexes={@ORM\Index(name="csmanager_config_configuration_indx", columns={"csmanager_config_name"})}
 *      )
 * @ORM\HasLifecycleCallbacks
 */
class Configuration
{
    const PASSWORD_FORGOT_NOREACT = 0;
    const PASSWORD_FORGOT_AUTOMAIL = 1;

    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_config_id", options={"comment":"Configuration id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_config_created", options={"comment":"Configuration date of creation"}) 
     */
    protected $created;

    /** 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_config_updated", options={"comment":"Configuration date of last update"}) 
     */
    protected $updated;

    /** 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The configuration deletion state"}, nullable=false, name="csmanager_config_deleted") 
     */
    protected $deleted;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="csmanager_config_name", options={"comment":"The configuration name"})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=false, name="csmanager_config_forgotPasswordReaction", options={"comment":"The configuration for the forgot password reaction. Refering to Cscfa\Bundle\CSManager\ConfigBundle\Entity\Configuration constant", "default":"1"})
     */
    protected $forgotPasswordReaction;
    
    /**
     * @ORM\Column(type="text", nullable=true, name="csmanager_config_forgotPasswordText", options={"comment":"The configuration for the forgot password information text."})
     */
    protected $forgotPasswordText;
    
    /**
     * Configuration constructor
     * 
     * Setup the entity
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->deleted = false;
        $this->updated = null;
        $this->forgotPasswordReaction = self::PASSWORD_FORGOT_AUTOMAIL;
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
     * Get name
     * 
     * Return the Configuration name
     * 
     * @return string - the name
     */
    public function getName()
    {
        return $this->name;
    }

   /**
    * Get forgot password reaction
    * 
    * Return the forgot pawword reaction
    * 
    * @return string - the password forgot reaction
    */
    public function getForgotPasswordReaction()
    {
        return $this->forgotPasswordReaction;
    }

    /**
     * Get forgot password text
     * 
     * Return the forgot pasword text
     * 
     * @return text - the password forgot text
     */
    public function getForgotPasswordText()
    {
        return $this->forgotPasswordText;
    }
     
    /**
     * Set updated
     * 
     * Setup the updated date
     * to the current date.
     * 
     * @return Configuration - the current entity
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
     * @return Configuration - the current entity
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
     * Set forgot password reaction
     * 
     * Set the forgot password reaction
     * 
     * @param string $forgotPasswordReaction - the password forgot reaction
     * 
     * @return Configuration - the current entity
     */
    public function setForgotPasswordReaction($forgotPasswordReaction)
    {
        $this->forgotPasswordReaction = $forgotPasswordReaction;
        return $this;
    }

    /**
     * Set name
     * 
     * Set the configuration name
     * 
     * @param string $name - the name
     * 
     * @return Configuration - the current configuration
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set forgot password text
     * 
     * Set the configuration forgot password text
     * 
     * @param string $forgotPasswordText - the forgot password text
     * 
     * @return Configuration - the current entity
     */
    public function setForgotPasswordText($forgotPasswordText)
    {
        $this->forgotPasswordText = $forgotPasswordText;
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