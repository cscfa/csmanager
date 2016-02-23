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
 * ProjectStatus
 *
 * The base ProjectStatus entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectStatusRepository")
 * @ORM\Table(name="csmanager_project_ProjectStatus")
 */
class ProjectStatus
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectStatus_id", options={"comment":"ProjectStatus id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** 
     * @ORM\Column(type="string", options={"comment":"The status name"}, nullable=false, name="csmanager_ProjectStatus_name") 
     */
    protected $name;

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
     * Get name
     * 
     * Return the status name
     * 
     * @return string - the status name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * Set the status name
     * 
     * @param string $name - the status name
     * 
     * @return ProjectStatus - the current instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
 
}