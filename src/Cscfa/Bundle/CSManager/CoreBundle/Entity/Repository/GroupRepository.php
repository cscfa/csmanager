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

/**
 * GroupRepository class.
 *
 * The GroupRepository class purpose feater to
 * quikly find a Group or a set of groups from
 * the database.
 *
 * @category Repository
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
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
                ->select("t0.name")
                ->from("Cscfa\Bundle\CSManager\CoreBundle\Entity\Group", "t0")
                ->distinct()
                ->getQuery()
                ->getResult();
            
            $result = array();
            foreach ($names as $value) {
                $result[] = $value['name'];
            }
            
            return $result;
        } catch (ORMInvalidArgumentException $e) {
            return null;
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
     * 
     * @return NULL
     */
    public function isExistingByName($name)
    {
        try {
            return $this->getEntityManager()
                ->createQuery("SELECT 1 FROM CscfaCSManagerCoreBundle:Group g WHERE g.name = :groupName")
                ->setParameter("groupName", $name)
                ->getOneOrNullResult();
        } catch (ORMInvalidArgumentException $e) {
            return null;
        }
    }
}
