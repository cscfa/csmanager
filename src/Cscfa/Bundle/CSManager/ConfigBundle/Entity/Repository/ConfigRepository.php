<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Repository
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ConfigBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ConfigRepository class.
 *
 * The ConfigRepository class purpose feater to
 * quikly find a Configuration or a set of configuration
 * from the database.
 *
 * @category Repository
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ConfigRepository extends EntityRepository
{
}
