<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
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

namespace Cscfa\Bundle\SecurityBundle\Entity\Repository;

use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\EntityRepository;

/**
 * GroupRepository class.
 *
 * The GroupRepository class purpose feater to
 * quikly find a Group or a set of groups from
 * the database.
 *
 * @category Repository
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class GroupRepository extends EntityRepository
{
    /**
     * Get all names.
     *
     * This method allow to
     * get all of the canonical
     * names of the existings
     * groups into the database.
     *
     * @return array
     */
    public function getAllName()
    {
        try {
            $names = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('t0.name')
                ->from("Cscfa\Bundle\SecurityBundle\Entity\Group", 't0')
                ->distinct()
                ->getQuery()
                ->getResult();

            $result = array();
            foreach ($names as $value) {
                $result[] = $value['name'];
            }

            return $result;
        } catch (ORMInvalidArgumentException $e) {
            return;
        }
    }

    /**
     * Check if a group name exist.
     *
     * This method allow to test if
     * a group exist by searching it
     * name existance.
     *
     * If exist, the method return 1,
     * else, the method will return null.
     *
     * @param string $name The name to check
     */
    public function isExistingByName($name)
    {
        try {
            return $this->getEntityManager()
                ->createQuery('SELECT 1 FROM CscfaSecurityBundle:Group g WHERE g.name = :groupName')
                ->setParameter('groupName', $name)
                ->getOneOrNullResult();
        } catch (ORMInvalidArgumentException $e) {
            return;
        }
    }
}
