<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a symfony bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Twig extension
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Twig\Extension;

use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\Security\Core\SecurityContext;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;

/**
 * ProjectRoleExtension class.
 *
 * The ProjectRoleExtension class define
 * the twig extension to check user role
 * on project attribute.
 *
 * @category Twig extension
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectRoleExtension extends \Twig_Extension
{
    
    /**
     * ProjectRoleExtension Attribute
     * 
     * This attribute store
     * the current security
     * context service.
     * 
     * @var SecurityContext
     */
    protected $context;

    /**
     * ProjectRoleExtension Attribute
     *
     * This attribute store
     * the current entity
     * manager service.
     *
     * @var EntityRepository
     */
    protected $entityRepository;
    
    /**
     * ProjectRoleExtension Attribute
     *
     * This attribute store
     * a project owner.
     * 
     * @var array
     */
    protected $registeredOwner = array(null, null, null);
    
    /**
     * Set arguments
     * 
     * Service argument definition
     * 
     * @param SecurityContext $context       The security context service
     * @param EntityManager   $entityManager The entity manager service
     */
    public function setArguments(SecurityContext $context, EntityManager $entityManager)
    {
        $this->context = $context;
        $this->entityRepository = $entityManager->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner");
    }
    
    /**
     * Get user
     * 
     * This method return the
     * current user from the
     * security context or null.
     * 
     * @return User
     */
    public function getUser()
    {
        if ($this->context->getToken() !== null) {
            return $this->context->getToken()->getUser();
        } else {
            return null;
        }
    }
    
    public function getOwner($project)
    {
        if($this->registeredOwner[0] === $project->getId() && $this->registeredOwner[1] === $this->getUser()->getId()){
            return $this->registeredOwner[2];
        } else {
            $this->registeredOwner[0] = $project->getId();
            $this->registeredOwner[1] = $this->getUser()->getId();
            return $this->registeredOwner[2] = $this->entityRepository->findOneBy(
                array(
                    "project"=>$this->registeredOwner[0],
                    "user"=>$this->registeredOwner[1]
                )
            );
        }
    }

    /**
     * Get functions
     * 
     * Return the function definitions
     * of the current twig extension.
     * 
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grantProjectAttribute', array(
                $this,
                'isGrantedProjectAttribute'
            ), array())
        );
    }
    
    /**
     * Is granted project attribute
     * 
     * This method check if a user
     * already have specific access
     * to project attribute.
     * 
     * @param string  $attribute The project attribute
     * @param boolean $read      Allow reading check
     * @param boolean $write     Allow writing check
     * 
     * @return boolean
     */
    public function isGrantedProjectAttribute($attribute, Project $project, $read = true, $write = false)
    {
        if ($this->getUser() !== null) {
            
            $owner = $this->getOwner($project);
            
            if ($owner instanceof ProjectOwner) {
                $roles = $owner->getRoles();
                
                foreach ($roles as $role) {
                    if ($role->getProperty() == $attribute || $role->getProperty() == "all") {
                        if ($read && !$role->getRead()) {
                            return false;
                        }
                        if ($write && !$role->getWrite()) {
                            return false;
                        }
                    }
                }
                
                return true;
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }

    /**
     * Get name
     * 
     * Return the current extension name.
     * 
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "cscfa_project.project_role.extension";
    }
}