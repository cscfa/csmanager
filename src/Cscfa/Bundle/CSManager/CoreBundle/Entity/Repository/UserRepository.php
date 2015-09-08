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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Entity\Repository;

use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * UserRepository class.
 *
 * The UserRepository class purpose feater to
 * quikly find a User or a set of users from
 * the database.
 *
 * @category Repository
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class UserRepository extends EntityRepository
{

    /**
     * Get all username.
     *
     * Return an array of all
     * username into canonical
     * state or null if none
     * exists.
     *
     * @return string[]|NULL
     */
    public function getAllUsername()
    {
        try {
            $usernames = $this->getEntityManager()
                ->createQueryBuilder()
                ->select("t0.usernameCanonical")
                ->from("Cscfa\Bundle\CSManager\CoreBundle\Entity\User", "t0")
                ->distinct()
                ->getQuery()
                ->getResult();
            
            $result = array();
            foreach ($usernames as $value) {
                $result[] = $value['usernameCanonical'];
            }
            
            return $result;
            
        } catch (ORMInvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * Get all email.
     *
     * Return an array of all
     * email into canonical
     * state or null if none
     * exists.
     *
     * @return string[]|NULL
     */
    public function getAllEmail()
    {
        try {
            $emails = $this->getEntityManager()
                ->createQueryBuilder()
                ->select("t0.emailCanonical")
                ->from("Cscfa\Bundle\CSManager\CoreBundle\Entity\User", "t0")
                ->distinct()
                ->getQuery()
                ->getResult();
            
            $result = array();
            foreach ($emails as $value) {
                $result[] = $value['emailCanonical'];
            }
            
            return $result;
            
        } catch (ORMInvalidArgumentException $e) {
            return null;
        }
    }
}
