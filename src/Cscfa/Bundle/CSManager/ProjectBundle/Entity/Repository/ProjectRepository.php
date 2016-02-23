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
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectRepository class.
 *
 * The ProjectRepository class purpose feater to
 * quikly find a Project or a set of projects
 * from the database.
 *
 * @category Repository
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Find existant
     * 
     * Return all of the not
     * deleted projects.
     * 
     * @return array
     */
    public function findExistant()
    {
        return $this->createQueryBuilder('e')
            ->where("e.deleted = false")
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Find readable
     * 
     * Return all of the projects
     * that a specified user have
     * a readable property on it.
     * 
     * @param string $userId The user id
     * 
     * @return array
     */
    public function findReadable($userId)
    {
        return $this->createQueryBuilder('e')
            ->join("e.projectOwners", 'o')
            ->join("o.user", 'u')
            ->join("o.roles", 'r')
            ->where("u.id = :id")
            ->setParameter('id', $userId)
            ->andWhere("r.read = true")
            ->andWhere("e.deleted = false")
            ->getQuery()
            ->getResult();
    }
}