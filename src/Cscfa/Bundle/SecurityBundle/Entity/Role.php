<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\SecurityBundle\Entity\Base\StackableObject;

/**
 * Role class.
 *
 * The Role class is the main role entity for database
 * persistance. It precise logical storage informations.
 *
 * This entity is stored into the cs_manager_role table
 * of the database and have an index called cs_manager_role_indx
 * that reference the name of the role to allow quikly
 * access into finding by name case.
 *
 * Featuring, this entity extend StackableObject to store
 * non specific informations about the creation date, the
 * user creator, the update date and the user updator.
 *
 * The repository of this entity is located into
 * the Entity\Repository folder of the core bundle.
 *
 * @category Entity
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 *       
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\SecurityBundle\Entity\Repository\RoleRepository")
 * @ORM\Table(
 *      name="cs_manager_role", 
 *      indexes={@ORM\Index(name="cs_manager_role_indx", columns={"role_name"})}
 * )
 * @ORM\Table(name="csmanager_core_role")
 */
class Role extends StackableObject
{

    /**
     * The id field
     *
     * The id parameter is the database
     * unique identity field, stored into GUID
     * format to improve security and allow
     * obfuscation of the total entry count.
     *
     * It is stored into role_id field into GUID
     * format, is unique and can't be null.
     *
     * @ORM\Column(
     *      type="guid", name="role_id", unique=true, nullable=false, options={"comment":"role identity"}
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * The name field
     *
     * The name of the role stored into role_name
     * fieldas index to offer string search optimisation.
     *
     * It can't be more long than 255 characters, is unique
     * and can't be null.
     *
     * @ORM\Column(type="string", length=255, name="role_name", unique=true, nullable=false, options={"comment":"navbar element name"})
     */
    protected $name;

    /**
     * The child field
     *
     * The child reference to the child entity. This
     * field can be null and duplicate. It's stored
     * into role_child field.
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(
     *      name="role_child", referencedColumnName="role_id"
     * )
     */
    protected $child;

    /**
     * Get the id of the role.
     *
     * The method getId() allow to get the identity
     * of the current role, formated into a secured
     * UUID format.
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the role.
     *
     * The method setName() allow to update
     * the current role name. No check are
     * made into this method but mysql database
     * will throw an exception if the name already
     * exist into an other role. Use the role exists
     * method of RoleManager to prevent diplication errors.
     *
     * This method return this to allow chained methods.
     *
     * @param string $name The new name to insert.
     * 
     * @see    Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager::roleExists()
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get the name of the role.
     *
     * The method getName() allow to get the current
     * role name as string.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the child of the role.
     *
     * The method getChild() allow to get the current
     * role child as Role entity. This is a reference
     * to another Role object so all of the Role methods
     * are allowed for it.
     *
     * @return Role
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set the child of the role.
     *
     * The method setChild() allow to set the current
     * role child. This is a reference to another
     * Role entoty, so a Role object must be passed
     * as argument of this method.
     *
     * No circular reference check are made into this
     * method, consider to use the hasCircularReference
     * method of the RoleManager class to prevent it.
     *
     * @param Role $child The new child to insert.
     * 
     * @see    Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager::hasCircularReference()
     * @return Role
     */
    public function setChild($child)
    {
        $this->child = $child;
        return $this;
    }
    
    /**
     * The to string method.
     * 
     * This method return the
     * current instance getName
     * result.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
