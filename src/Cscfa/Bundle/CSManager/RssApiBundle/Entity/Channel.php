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
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\RssApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * The base Channel entity for the
 * Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\RssApiBundle\Entity\Repository\ChannelRepository")
 * @ORM\Table(name="csmanager_rss_channel")
 * @ORM\HasLifecycleCallbacks
 */
class Channel {

    /**
     * Id
     * 
     * The channel id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_Channel_id", options={"comment":"channel id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * Created
     * 
     * The entity creation date
     * 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_Channel_created", options={"comment":"Channel date of creation"}) 
     */
    protected $created;

    /** 
     * Updated
     * 
     * The entity update date
     * 
     * @ORM\Column(type="datetime", nullable=true, name="csmanager_Channel_updated", options={"comment":"Channel date of last update"}) 
     */
    protected $updated;

    /** 
     * Deleted
     * 
     * The entity deletion state
     * 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The Channel deletion state"}, nullable=false, name="csmanager_Channel_deleted") 
     */
    protected $deleted;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser")
     * @ORM\JoinColumn(name="csmanager_Channel_user_id", referencedColumnName="csmanager_rss_user_id")
     */
    protected $user;

    /** 
     * Title
     * 
     * The channel title
     * 
     * @ORM\Column(type="string", length=255, options={"comment":"The Channel name"}, nullable=false, name="csmanager_Channel_title") 
     * @Assert\NotBlank()
     */
    protected $name;

    /** 
     * Description
     * 
     * The channel description
     * 
     * @ORM\Column(type="text", options={"comment":"The Channel description"}, nullable=false, name="csmanager_Channel_description") 
     * @Assert\NotBlank()
     */
    protected $description;

    /** 
     * Item types
     * 
     * The channel item types to display
     * 
     * @ORM\Column(type="text", options={"comment":"The Channel items types"}, nullable=false, name="csmanager_Channel_item_types") 
     */
    protected $itemTypes;

    /**
     * HashId
     *
     * The channel id hash
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The Channel hash id"}, nullable=false, name="csmanager_Channel_hash_id")
     */    
    protected $hashId;
    
    /**
     * Channel constructor
     * 
     * Setup the entity
     * 
     * @param string $name        The channel name
     * @param string $description The channel description
     * @param array  $itemTypes   The types of item to display
     */
    public function __construct(RssUser $user = null,$name = null, $description = null, array $itemTypes = array())
    {
        $this->created = new \DateTime();
        $this->deleted = false;
        $this->updated = null;
        $this->name = $name;
        $this->description = $description;
        $this->setItemTypes($itemTypes);
        $this->user = $user;
        
        $crypto = true;
        $hashId = openssl_random_pseudo_bytes(6, $crypto);
        $this->hashId = bin2hex($hashId);
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
    
    /**
     * Get id
     * 
     * This method return
     * the channel id
     * 
     * @return string
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get creation date
     * 
     * This method return
     * the channel creation date
     * 
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Get update date
     * 
     * This method return
     * the channel update date
     * 
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }
    
    /**
     * Get deletion state
     * 
     * This method return
     * the channel deletion state
     * 
     * @return boolean
     */
    public function getDeleted() {
        return $this->deleted;
    }
    
    /**
     * Set deletion state
     * 
     * This method allow to
     * set the channel deletion state
     * 
     * @param boolean $deleted The channel deletion state
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;
        return $this;
    }
    
    /**
     * Get user
     * 
     * This method return
     * the channel user
     * 
     * @return RssUser
     */
    public function getUser() {
        return $this->user;
    }
    
    /**
     * Set user
     * 
     * This method allow to
     * set the channel user
     * 
     * @param RssUser $user The channel user
     */
    public function setUser(RssUser $user) {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get name
     * 
     * This method return
     * the channel name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set name
     * 
     * This method allow to
     * set the channel name
     * 
     * @param string $name The channel name
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get description
     * 
     * This method return
     * the channel description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set description
     * 
     * This method allow to
     * set the channel description
     * 
     * @param string $description The channel description
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get items types
     * 
     * This method return
     * the channel items types
     * 
     * @return array
     */
    public function getItemTypes() {
        return explode(":%:", $this->itemTypes);
    }
    
    /**
     * Set items types
     * 
     * This method allow to
     * set the channel items types
     * 
     * @param array $itemTypes The channel items types
     */
    public function setItemTypes(array $itemTypes) {
        $this->itemTypes = implode(":%:", $itemTypes);
        return $this;
    }
    
    /**
     * Get hashId
     * 
     * This method return
     * the channel hashId
     * 
     * @return string
     */
    public function getHashId() {
        return $this->hashId;
    }
 
}
