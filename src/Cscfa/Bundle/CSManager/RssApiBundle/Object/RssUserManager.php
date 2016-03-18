<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\RssApiBundle\Object;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser;

/**
 * RssUserManager class.
 *
 * The RssUserManager implement
 * access method to rss user.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class RssUserManager
{
    /**
     * Doctrine.
     *
     * This attribute store
     * the application registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Set arguments.
     *
     * This method allow
     * to init the RssUserManager
     * attributes
     *
     * @param Registry $doctrine The doctrine entity manager
     */
    public function setArguments(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get rss user.
     *
     * This method return the
     * current RssUser
     *
     * @param User $user The current user
     *
     * @return RssUser
     */
    public function getRssUser(User $user)
    {
        $repo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser");

        return $repo->findOneByUser($user->getId());
    }

    /**
     * Create rss user.
     *
     * This method create if
     * not exist and return
     * a RssUser for the current
     * user.
     *
     * @param User $user The current user instance
     *
     * @return RssUser
     */
    public function createRssUser(User $user)
    {
        $rssUser = $this->getRssUser($user);

        if (!$rssUser) {
            $rssUser = new RssUser($user);
            $this->doctrine->getManager()->persist($rssUser);
            $this->doctrine->getManager()->flush();
        }

        return $rssUser;
    }

    /**
     * Get rss channels.
     *
     * This method return
     * the channels of an
     * user.
     *
     * @param User $user The current user
     */
    public function getRssChannels(User $user)
    {
        $rssUser = $this->getRssUser($user);

        if ($rssUser) {
            $repo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel");

            return $repo->findByUser($this->getRssUser($user)->getId());
        } else {
            return [];
        }
    }

    /**
     * Get rss user by token.
     *
     * This method return a
     * RssUser find by it's
     * token or null.
     *
     * @param string $token The RssUser token
     *
     * @return RssUser|null
     */
    public function getRssUserByToken($token)
    {
        $repo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssUser");

        return $repo->findOneByToken($token);
    }
}
