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

use Doctrine\ORM\Mapping as ORM;

/**
 * TrackerLink
 *
 * The base Tracker Links entity 
 * for the Cscfa track manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\TrackBundle\Entity\Repository\TrackerLinkRepository")
 * @ORM\Table(name="csmanager_tracking_TrackerLink",
 *      indexes={@ORM\Index(name="csmanager_tracking_trackerLink_indx", columns={"csmanager_TrackerLink_className", "csmanager_TrackerLink_linkedId"})}
 *      )
 */
class TrackerLink {
    
    /**
     * Id
     * 
     * The tracker id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_TrackerLink_id", options={"comment":"TrackerLink id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /** 
     * ClassName
     * 
     * The tracker linked entity classname
     * 
     * @ORM\Column(type="string", length=255, options={"comment":"The TrackerLink class name"}, nullable=false, unique=false, name="csmanager_TrackerLink_className") 
     */
    protected $className;
    
    /** 
     * Linked id
     * 
     * The tracker linked entity id
     * 
     * @ORM\Column(type="string", length=255, options={"comment":"The TrackerLink linked entity id"}, nullable=false, unique=false, name="csmanager_TrackerLink_linkedId") 
     */
    protected $linkedId;

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
     * Get className
     * 
     * This method return the
     * linked entity className
     * 
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }
    
    /**
     * Set className
     * 
     * This method allow to set
     * the linked entity className.
     * 
     * @param string $className The linked entity className
     * 
     * @return TrackerLink
     */
    public function setClassName($className) {
        $this->className = $className;
        return $this;
    }
    
    /**
     * Get linked id
     * 
     * This method return the
     * linked entity id
     * 
     * @return string
     */
    public function getLinkedId() {
        return $this->linkedId;
    }
    
    /**
     * Set linked id
     * 
     * This method allow to set
     * the linked entity id.
     * 
     * @param string $linkedId The linked entity id
     * 
     * @return TrackerLink
     */
    public function setLinkedId($linkedId) {
        $this->linkedId = $linkedId;
        return $this;
    }
 
}
