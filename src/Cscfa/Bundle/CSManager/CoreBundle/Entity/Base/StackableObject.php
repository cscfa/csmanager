<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Entity
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\CSManager\CoreBundle\Entity\User;

/**
 * StackableObject class.
 *
 * The StackableObject class is an abstract
 * definition that describe how to store informations
 * about user modifications action.
 *
 * @category Entity
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
abstract class StackableObject
{

    /**
     * The creation date.
     *
     * This variable is used to
     * store the instance creation
     * date and time.
     *
     * It stored under datetime
     * format and cannot be null.
     *
     * @ORM\Column(type="datetime", nullable=false, options={"comment":"Element creation date"})
     */
    protected $createdAt;

    /**
     * The update date.
     *
     * This variable is used to store
     * the instance update date and
     * time.
     *
     * It stored under datetime
     * format and cannot be null.
     *
     * @ORM\Column(type="datetime", nullable=true, options={"comment":"Element last update date"})
     */
    protected $updatedAt;

    /**
     * The user creator.
     *
     * This variable is used to store
     * the user creator.
     *
     * It's a relation to the User table
     * and refer to the user identity.
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_creator_id", referencedColumnName="user_id")
     */
    protected $createdBy;

    /**
     * The user updator.
     *
     * This variable is used to store
     * the user updator.
     *
     * It's a relation to the User table
     * and refer to the user identity.
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_updator_id", referencedColumnName="user_id")
     */
    protected $updatedBy;

    /**
     * Get the creation date.
     *
     * This method allow to get the instance
     * creation date as DateTime instance
     * or null if never set.
     *
     * @see    \DateTime
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date.
     *
     * This method allow to set up the instance
     * creation date. It take a DateTime instance
     * on argument and return the current
     * StackableObject for chained methods.
     *
     * @param \DateTime $createdAt The new datetime to store.
     *         
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Set the last update date.
     *
     * This method allow to set up the current
     * instance update date.It take a DateTime
     * instance on argument and return the current
     * StackableObject for chained methods.
     *
     * @param \DateTime $updatedAt The new DateTime to store.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the last update date.
     *
     * This method allow to get the last instance
     * update date. It return a DateTime instance
     * or null if never set.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get the user creator.
     *
     * This method allow to get the user creator
     * of the current instance. It return a User
     * instance or null if never set.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the user creator.
     *
     * This method allow to set up the
     * current instance user creator. It
     * take a User as argument and return
     * the current StackableObject for
     * chained methods.
     *
     * @param User $createdBy The User instance to store as creator.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * Get the user updator.
     *
     * This method allow to get the
     * current instance user updator.
     * It return a User instance or
     * null if never set.
     *
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set the user updator.
     *
     * This method allow to set up the
     * current instance updator. It take
     * a User instance as argument and
     * return the current StackableObject
     * for chained methods.
     *
     * @param User $updatedBy The User instance to store as updator.
     * 
     * @return \Cscfa\Bundle\CSManager\CoreBundle\Entity\Base\StackableObject
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}