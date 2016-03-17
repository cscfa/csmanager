<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Repository
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\RssApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RssItemRepository class.
 *
 * The RssItemRepository class purpose feater to
 * quikly find a RssItem or a set of RssItems
 * from the database.
 *
 * @category Repository
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class RssItemRepository extends EntityRepository
{
}
