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

namespace Cscfa\Bundle\CSManager\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type.
 *
 * The type entity for the
 * Cscfaproject manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UserBundle\Entity\Repository\TypeRepository")
 * @ORM\Table(name="csmanager_user_type")
 */
class Type
{
    /**
     * The id field.
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into user_id field into GUID
     * format, is unique and can't be null.
     *
     * @ORM\Column(
     *      type="guid", name="type_id", unique=true, nullable=false, options={"comment":"type identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $typeId;

    /**
     * The label.
     *
     * The type label
     *
     * @var string
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The type label"},
     *      nullable=false,
     *      name="csmanager_type_label"
     * )
     */
    protected $label;

    /**
     * Get id.
     *
     * Return the entity UUID
     *
     * @return string - the entity UUID
     */
    public function getId()
    {
        return $this->typeId;
    }

    /**
     * Get label.
     *
     * Get the type label
     *
     * @return string - the type label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label.
     *
     * Set the type label
     *
     * @param string $label - the type label
     *
     * @return Type - the current instance
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }
}
