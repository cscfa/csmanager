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
use Cscfa\Bundle\CSManager\RssApiBundle\Interfaces\RssItemAuthInterface;
use Cscfa\Bundle\CSManager\RssApiBundle\Object\RssAuthInfo;

/**
 * RssItem
 *
 * The base rss item entity for the
 * Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\RssApiBundle\Entity\Repository\RssItemRepository")
 * @ORM\Table(name="csmanager_rss_item")
 */
class RssItem {

    /**
     * Id
     * 
     * The channel id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_rss_item_id", options={"comment":"Rss item id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * Created
     * 
     * The entity creation date
     * 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_rss_item_created", options={"comment":"Rss item date of creation"}) 
     */
    protected $created;

    /**
     * Type
     *
     * The item type
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item type"}, nullable=false, name="csmanager_rss_item_type")
     */
    protected $type;

    /**
     * Title
     *
     * The item title
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item name"}, nullable=false, name="csmanager_rss_item_title")
     */
    protected $title;

    /**
     * Link
     *
     * The item link
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item link"}, nullable=false, name="csmanager_rss_item_link")
     */
    protected $link;

    /**
     * Description
     *
     * The item description
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item description"}, nullable=false, name="csmanager_rss_item_description")
     */
    protected $description;

    /**
     * Author
     *
     * The item author
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item author"}, nullable=true, name="csmanager_rss_item_author")
     */
    protected $author;

    /**
     * category
     *
     * The item category
     *
     * @ORM\Column(type="string", length=255, options={"comment":"The rss item category"}, nullable=true, name="csmanager_rss_item_category")
     */
    protected $category;

    /**
     * Authorization service
     * 
     * The authorization service
     * that must be called to
     * grant user athorization
     * and extra informations.
     *
     * @ORM\Column(type="text", options={"comment":"The authorized service and extra informations"}, nullable=false, name="csmanager_rss_item_authService")
     */
    protected $authService;
    
    /**
     * Channel constructor
     * 
     * Setup the entity
     * 
     * @param string               $type        The item type
     * @param string               $title       The item title
     * @param string               $link        The item link
     * @param string               $description The item description
     * @param string               $author      The item author
     * @param string               $category    The item category
     * @param RssItemAuthInterface $authService The authorization service
     * @param array                $extraAuth   The extra authorization service informations
     */
    public function __construct($type = null, $title = null, $link = null, $description = null, $author = null, $category = null, RssItemAuthInterface $authService = null, array $extraAuth = array())
    {
        $this->created = new \DateTime();
        $this->type = $type;
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->author = $author;
        $this->category = $category;
        $this->setAuthService($authService, $extraAuth);
    }
    
    /**
     * Get id
     * 
     * This method return 
     * the item id
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
     * the item creation date
     * 
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Get type
     * 
     * This method return 
     * the item type
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Set type
     * 
     * This method allow to set 
     * the item type
     * 
     * @param string $type The item type
     * 
     * @return RssItem
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Get title
     * 
     * This method return 
     * the item title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Set title
     * 
     * This method allow to set 
     * the item title
     * 
     * @param string $title The item title
     * 
     * @return RssItem
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get link
     * 
     * This method return 
     * the item link
     * 
     * @return string
     */
    public function getLink() {
        return $this->link;
    }
    
    /**
     * Set link
     * 
     * This method allow to set 
     * the item link
     * 
     * @param string $link The item link
     * 
     * @return RssItem
     */
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }
    
    /**
     * Get description
     * 
     * This method return 
     * the item description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set description
     * 
     * This method allow to set 
     * the item description
     * 
     * @param string $description The item description
     * 
     * @return RssItem
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get author
     * 
     * This method return 
     * the item author
     * 
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }
    
    /**
     * Set author
     * 
     * This method allow to set 
     * the item author
     * 
     * @param string $author The item author
     * 
     * @return RssItem
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }
    
    /**
     * Get category
     * 
     * This method return 
     * the item category
     * 
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }
    
    /**
     * Set category
     * 
     * This method allow to set 
     * the item category
     * 
     * @param string $category The item category
     * 
     * @return RssItem
     */
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Get authorization service
     * 
     * @return RssAuthInfo
     */
    public function getAuthService() {
        return new RssAuthInfo($this->authService);
    }
    
    /**
     * Set authorization service
     * 
     * This method allow to
     * store the authorize 
     * service informations.
     * 
     * @param RssItemAuthInterface $authService The autorized service
     * @param array                $extraAuth   The extra informations
     * 
     * @return RssItem
     */
    public function setAuthService(RssItemAuthInterface $authService = null, array $extraAuth = array()) {
        
        if ($authService !== null) {
            foreach ($extraAuth as $key=>$value) {
                $authService->add($key, $value);
            }
            
            $this->authService = $authService->dumpDataInfo();
        }
        return $this;
    }
    
}
