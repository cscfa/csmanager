<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Object;

use Cscfa\Bundle\CSManager\RssApiBundle\Object\RssItemAuthBase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * ProjectCreatedAuth class.
 *
 * The ProjectCreatedAuth implement
 * access method to project creation
 * rss system.
 *
 * @category Object
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectCreatedAuth extends RssItemAuthBase {
    
    /**
     * Doctrine
     * 
     * This property store
     * the doctrine service
     * 
     * @var Registry
     */
    protected $doctrine;
    
    /**
     * Router
     * 
     * This property store
     * the router service
     * 
     * @var Router
     */
    protected $router;
    
    /**
     * Set registry
     * 
     * This method allow
     * to set the doctrine
     * registry service.
     * 
     * @param Registry $doctrine
     */
    public function setRegistry(Registry $doctrine){
        $this->doctrine = $doctrine;
    }
    
    /**
     * Set router
     * 
     * This method allow to
     * set the router service.
     * 
     * @param Router $router The router service
     */
    public function setRouter(Router $router){
        $this->router = $router;
    }
    
	/**
	 * Is authorized
	 * 
	 * Check if an item is
	 * currently allowed to
	 * be showed by the current 
	 * user.
	 * 
	 * @param RssItem $item The rss item
	 * @param User    $user The current user
	 * 
	 * @return boolean
	 */
    public function isAuthorized(RssItem $item, User $user) {
        if ($user->hasRole("ROLE_ADMIN")) {
            return true;
        }else{
            $extra = $item->getAuthService()->getExtra();
            $projectId = $extra["project"];
            
            $query = $this->doctrine->getManager()->createQuery(
                "SELECT o
                FROM Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project p
                JOIN Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner o
                WHERE p.id = :project
                AND o.user = :user
                "
            )->setParameter("project", $projectId)
            ->setParameter("user", $user->getId());
            
            try {
                $result = $query->getSingleResult();
                
                if ($result) {
                    return true;
                }else{
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Get name
     *
     * Return the service
     * name
     *
     * @return string
     */
    public function getName() {
        return "project.created.rss.auth";
    }
    
    /**
     * Create
     * 
     * This method create 
     * a new RssItem into 
     * the database.
     * 
     * @param Project $project The current project
     */
    public function create(Project $project){
        $rss = new RssItem(
            "project.event.created",
            sprintf("a new project was created", $project->getName()),
            $this->router->generate("cscfa_cs_manager_project_home"),
            sprintf("The project %s is created. Description : %s", $project->getName(), $project->getSummary()),
            null,
            "Project",
            $this,
            array("project"=>$project->getId())
        );
        
        $this->doctrine->getManager()->persist($rss);
        $this->doctrine->getManager()->flush();
    }
}
