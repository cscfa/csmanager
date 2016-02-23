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
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectRole
 *
 * The base ProjectRole entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectRoleRepository")
 * @ORM\Table(name="csmanager_project_ProjectRole")
 */
class ProjectRole
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectRole_id", options={"comment":"ProjectRole id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="string", options={"comment":"The Project property"}, nullable=false, name="csmanager_ProjectRole_property") 
     */
    protected $property;

    /** 
     * @ORM\Column(type="boolean", options={"default" = true, "comment":"The ProjectRole reading permission"}, nullable=false, name="csmanager_ProjectRole_read") 
     */
    protected $read;

    /** 
     * @ORM\Column(type="boolean", options={"default" = false, "comment":"The ProjectRole writing permission"}, nullable=false, name="csmanager_ProjectRole_write") 
     */
    protected $write;

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
     * Get property
     * 
     * Return the property
     * 
     * @return string - the property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set property
     * 
     * Set the property
     * 
     * @param string $property - the property
     * 
     * @return ProjectRole - the current instance
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Get read
     * 
     * Return the reading permission
     * 
     * @return boolean - the reading permission
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set read
     * 
     * Set the reading permission
     * 
     * @param boolean $read - the reading permission
     * 
     * @return ProjectRole - the current instance
     */
    public function setRead($read)
    {
        $this->read = $read;
        return $this;
    }

    /**
     * Get write
     * 
     * Return the writing permission
     * 
     * @return boolean - the writing permission
     */
    public function getWrite()
    {
        return $this->write;
    }

    /**
     * Set write
     * 
     * Set the writing permission
     * 
     * @param boolean $write - the writing permission
     * 
     * @return ProjectRole - the current instance
     */
    public function setWrite($write)
    {
        $this->write = $write;
        return $this;
    }
 
}