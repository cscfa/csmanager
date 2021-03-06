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
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\TrackBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TrackerLinkRepository class.
 *
 * The TrackerLinkRepository class purpose feater to
 * quikly find a TrackerLink or a set of TrackerLinks
 * from the database.
 *
 * @category Repository
 * @package  CscfaCSManagerTrackBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class TrackerLinkRepository extends EntityRepository {
}