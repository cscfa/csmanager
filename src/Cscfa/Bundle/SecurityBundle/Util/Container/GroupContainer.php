<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Container
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\SecurityBundle\Util\Container;

use Cscfa\Bundle\SecurityBundle\Util\Provider\GroupProvider;
use Cscfa\Bundle\SecurityBundle\Util\Manager\GroupManager;

/**
 * GroupContainer class.
 *
 * The GroupContainer class purpose feater to
 * manage the group provider and manager.
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @version  Release: 1.1
 *
 * @link     http://cscfa.fr
 */
class GroupContainer
{
    /**
     * Provider.
     *
     * The group provider service
     *
     * @var GroupProvider
     */
    protected $provider;

    /**
     * Manager.
     *
     * The group manager service
     *
     * @var GroupManager
     */
    protected $manager;

    /**
     * Group container constructor.
     *
     * This register the group manager
     * service and the group provider
     * service.
     *
     * @param GroupProvider $provider - the group provider service
     * @param GroupManager  $manager  - the group manager service
     */
    public function __construct(GroupProvider $provider, GroupManager $manager)
    {
        $this->provider = $provider;
        $this->manager = $manager;
    }

    /**
     * Get provider.
     *
     * Return the group provider
     * service.
     *
     * @return GroupProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get manager.
     *
     * Return the group manager
     * service.
     *
     * @return GroupManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
