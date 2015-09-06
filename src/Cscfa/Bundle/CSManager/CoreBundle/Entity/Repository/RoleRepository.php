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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;

/**
 * RoleRepository class.
 *
 * The RoleRepository class purpose feater to
 * quikly find a Role or a set of roles from
 * the database.
 *
 * @category Repository
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleRepository extends EntityRepository
{

    /**
     * Low charge check for name.
     *
     * This method get '1' from the database
     * in replacement of a Role instance with
     * a specific name.
     *
     * This method is a low charge result
     * and can be used for test.
     *
     * @param string $name The name to find
     * 
     * @return integer|null
     */
    public function isExistingByName($name)
    {
        try {
            return $this->getEntityManager()
                ->createQuery("SELECT 1 FROM CscfaCSManagerCoreBundle:Role r WHERE r.name = :roleName")
                ->setParameter("roleName", $name)
                ->getOneOrNullResult();
        } catch (ORMInvalidArgumentException $e) {
            return null;
        }
    }
}
