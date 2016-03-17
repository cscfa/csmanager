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

namespace Cscfa\Bundle\CSManager\RssApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * RssUser.
 *
 * The base Channel user entity
 * for the Cscfa project manager
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\RssApiBundle\Entity\Repository\RssUserRepository")
 * @ORM\Table(name="csmanager_rss_user")
 * @ORM\HasLifecycleCallbacks
 */
class RssUser
{
    /**
     * Id.
     *
     * The rss user id
     *
     * @ORM\Column(type="guid", nullable=false, name="csmanager_rss_user_id", options={"comment":"Rss user id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $userId;

    /**
     * @ORM\ManyToOne(targetEntity="Cscfa\Bundle\SecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="csmanager_Channel_user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * token.
     *
     * The user token
     *
     * @ORM\Column(
     *      type="string",
     *      length=255,
     *      options={"comment":"The rss user token"},
     *      nullable=false,
     *      name="csmanager_rss_user_token"
     * )
     */
    protected $token;

    /**
     * Enabled.
     *
     * The user enable state
     *
     * @var bool
     */
    protected $enabled;

    /**
     * RssUser constructor.
     *
     * The RssUser default constructor
     *
     * @param User $user The User instance
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = uniqid();
    }

    /**
     * Load.
     *
     * PostLoad the entity to
     * store the enable state
     *
     * @ORM\PostLoad()
     */
    protected function load()
    {
        $this->enabled = $this->user->isEnabled() && !$this->user->isExpired() && !$this->user->isLocked();
    }

    /**
     * Get id.
     *
     * This method return
     * the RssUser id
     *
     * @return string
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * Get user.
     *
     * This method return
     * the RssUser user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * This method allow to set
     * the RssUser user
     *
     * @param User $user The RssUser user
     *
     * @return RssUser
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get token.
     *
     * This method return
     * the RssUser token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token.
     *
     * This method allow to set
     * the RssUser token
     *
     * @param string $token The RssUser token
     *
     * @return RssUser
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get enable state.
     *
     * This method return
     * the RssUser enable state
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
