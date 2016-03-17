<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity;

use Cscfa\Bundle\CSManager\UseCaseBundle\Interfaces\TaskStatusInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * UseCaseStatus class.
 *
 * The base UseCaseStatus entity for the
 * Cscfa project manager
 *
 * @category Entity
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Repository\UseCaseStatusRepository")
 * @ORM\Table(name="csmanager_usecase_UseCaseStatus",
 *      indexes={@ORM\Index(name="csmanager_usecase_UseCaseStatus_indx", columns={"csmanager_UseCaseStatus_name"})}
 *      )
 */
class UseCaseStatus implements TaskStatusInterface
{
    /**
     * Id.
     *
     * The UseCase id
     *
     * @ORM\Column(
     *      type="guid",
     *      nullable=false,
     *      name="csmanager_UseCaseStatus_id",
     *      options={"comment":"UseCaseStatus id"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    protected $statusId;

    /**
     * Name.
     *
     * The UseCase name
     *
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      unique=true,
     *      nullable=false,
     *      name="csmanager_UseCaseStatus_name",
     *      options={"comment":"UseCaseStatus name"}
     * )
     *
     * @var string
     */
    protected $name;

    /**
     * Description.
     *
     * The UseCase description
     *
     * @ORM\Column(
     *      type="text",
     *      nullable=true,
     *      name="csmanager_UseCaseStatus_description",
     *      options={"comment":"UseCaseStatus description"}
     * )
     *
     * @var string
     */
    protected $description;

    /**
     * Get id.
     *
     * This method return the
     * current entity id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->statusId;
    }

    /**
     * Set name.
     *
     * This method allow to set
     * the status name.
     *
     * @param string $name
     *
     * @return TaskStatusInterface
     *
     * @see    TaskStatusInterface::setName()
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * This method return the
     * status name.
     *
     * @return string
     *
     * @see    TaskStatusInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * This method allow to set
     * the status description.
     *
     * @param string $description
     *
     * @return TaskStatusInterface
     *
     * @see    TaskStatusInterface::setDescription()
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * This method return the
     * status description.
     *
     * @return string
     *
     * @see    TaskStatusInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->description;
    }
}
