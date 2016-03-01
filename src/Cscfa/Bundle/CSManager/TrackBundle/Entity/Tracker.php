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
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\TrackBundle\Entity;

use Cscfa\Bundle\SecurityBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tracker
 *
 * The base Tracker entity for the
 * Cscfa track manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\TrackBundle\Entity\Repository\TrackerRepository")
 * @ORM\Table(name="csmanager_tracking_Tracker")
 */
class Tracker {
    
    /**
     * Id
     * 
     * The tracker id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_Tracker_id", options={"comment":"Tracker id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * Date
     * 
     * The entity creation date
     * 
     * @ORM\Column(type="datetime", nullable=false, name="csmanager_Tracker_created", options={"comment":"Tracker date of creation"}) 
     */
    private $date;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\SecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="csmanager_Tracker_user_id", referencedColumnName="user_id")
     */
    protected $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="TrackerLink")
     * @ORM\JoinTable(name="tk_csmanager_Tracker_TrackerLink",
     *      joinColumns={@ORM\JoinColumn(name="tracker_id", referencedColumnName="csmanager_Tracker_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="trackerLink_id", referencedColumnName="csmanager_TrackerLink_id")}
     *      )
     */
    protected $links;
    
    /**
     * Tracker constructor
     * 
     * Default constructor
     * that init the creation
     * date.
     */
    public function __construct(){
        $this->date = new \DateTime();
    }
    
    /**
     * Get id
     * 
     * This method return the
     * entity id
     * 
     * @return string
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get date
     * 
     * This method return
     * the entity date of
     * creation.
     * 
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }
    
    /**
     * Set date
     * 
     * This method allow to set 
     * the date of creation of
     * the current entity
     * 
     * @param \DateTime $date The date of creation
     * 
     * @return Tracker
     */
    private function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }
    
    /**
     * Get user
     * 
     * This method return
     * the user that it is
     * tracked
     * 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }
    
    /**
     * Set user
     * 
     * This method allow to set
     * the tracked user.
     * 
     * @param User $user The tracked user
     * 
     * @return Tracker
     */
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get links
     * 
     * This method return an
     * array of links that are
     * based on the tracked
     * event.
     * 
     * @return ArrayCollection
     */
    public function getLinks() {
        return $this->links;
    }
    
    /**
     * Set links
     * 
     * This method allow to set
     * the links that are
     * based on the tracked
     * event.
     * 
     * @param array $links The links
     * 
     * @return Tracker
     */
    public function setLinks($links) {
        $this->links = $links;
        return $this;
    }
 
}
