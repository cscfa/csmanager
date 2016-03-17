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

namespace Cscfa\Bundle\CSManager\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProjectLink.
 *
 * The base ProjectLink entity for the
 * Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository\ProjectLinkRepository")
 * @ORM\Table(name="csmanager_project_ProjectLink")
 */
class ProjectLink
{
    /**
     * @ORM\Column(type="guid", nullable=false, name="csmanager_ProjectLink_id", options={"comment":"ProjectLink id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $linkId;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="links")
     * @ORM\JoinColumn(name="csmanager_ProjectLink_id", referencedColumnName="csmanager_Project_id")
     */
    protected $project;

    /**
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The link"},
     *      nullable=false,
     *      name="csmanager_ProjectLink_link"
     * )
     * @Assert\NotBlank()
     */
    protected $link;

    /**
     * Get id.
     *
     * Return the entity id.
     *
     * @return string - return the entity id
     */
    public function getId()
    {
        return $this->linkId;
    }

    /**
     * Get project.
     *
     * Return the referenced
     * project.
     *
     * @return Project - the referenced project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project.
     *
     * Set the referenced
     * project
     *
     * @param Project $project - the referenced project
     *
     * @return ProjectLink - the current entity
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get link.
     *
     * Return the link.
     *
     * @return string - the link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link.
     *
     * Set the link
     *
     * @param string $link - the link
     *
     * @return ProjectLink - the current entity
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }
}
