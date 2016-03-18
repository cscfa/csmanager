<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\RssApiBundle\Interfaces;

use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\RssApiBundle\Object\RssAuthInfo;

/**
 * RssItemAuthInterface interface.
 *
 * The RssItemAuthInterface implement
 * access method to rss item authorization
 * service.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface RssItemAuthInterface
{
    /**
     * Add.
     *
     * This method allow to
     * add several informations
     * to the authentifyer.
     *
     * @param string $key   The storage key
     * @param string $value The value to store
     */
    public function add($key, $value);

    /**
     * Dump data information.
     *
     * This method return the
     * serialized data that
     * will be stored into
     * the database.
     *
     * @return string
     */
    public function dumpDataInfo();

    /**
     * Parse data information.
     *
     * This method return the
     * unserialized data of the
     * stored informations.
     *
     * @param RssAuthInfo $data
     */
    public function parseDataInfo(RssAuthInfo $data);

    /**
     * Get name.
     *
     * Return the service
     * name
     *
     * @return string
     */
    public function getName();

    /**
     * Is authorized.
     *
     * Check if an item is
     * currently allowed to
     * be showed by the current
     * user.
     *
     * @param RssItem $item The rss item
     * @param User    $user The current user
     *
     * @return bool
     */
    public function isAuthorized(RssItem $item, User $user);
}
