<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Controller
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectRole;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\CSManager\ProjectBundle\Twig\Extension\ProjectRoleExtension;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectOwnerEvent;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectRoleEvent;

/**
 * RoleController class.
 *
 * The RoleController implement
 * access method to project user's
 * roles.
 *
 * @category Controller
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class RoleController extends Controller
{
    protected $transDomain = "CscfaCSManagerProjectBundle_controller_RoleController";
    
    /**
     * Get addable users
     * 
     * This method return a
     * set of users that are 
     * not owners of the current
     * project.
     * 
     * @param Project $project The current project
     * 
     * @return array
     */
    protected function getAddableUsers($project)
    {
        $projectOwners = $project->getProjectOwners();
        $currentOwners = [];
        foreach ($projectOwners as $owner) {
            $currentOwners[] = $owner->getUser();
        }
        
        $users = $this->getDoctrine()->getRepository("Cscfa\Bundle\SecurityBundle\Entity\User")->findAll();
        $addableUsers = array_diff($users, $currentOwners);
        
        $addUserArray = [];
        foreach ($addableUsers as $addableUser) {
            $addUserArray[$addableUser->getId()] = $addableUser->getUsername();
        }
        
        return $addUserArray;
    }
    
    /**
     * get addable form
     * 
     * This method return
     * the user adding form
     * for the current project.
     * 
     * @param Project $project The current project
     * 
     * @return Form
     */
    protected function getAddableForm($project)
    {
        return $this->createFormBuilder([])
            ->setAction($this->generateUrl("cscfa_cs_manager_project_index_role_add_user", array("project"=>$project->getId())))
            ->add(
                "selected", 
                "choice",
                array(
                    "label"=>$this->get("translator")->trans("getAddable.selected.label", [], $this->transDomain),
                    "choices"=>$this->getAddableUsers($project),
                    "placeholder"=>$this->get("translator")->trans("getAddable.selected.placeholder", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"form-control",
                    )
                )
            )
            ->add(
                "submit",
                "submit",
                array(
                    "label"=>$this->get("translator")->trans("getAddable.submit.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"btn btn-success"
                    )
                )
            )
            ->getForm();
    }
    
    /**
     * index action
     * 
     * This action return the
     * project user's role 
     * management index page
     * 
     * @param Project $project The current project
     * @template
     */
    public function indexAction(Project $project)
    {
        return array("project"=>$project, "addableUsers"=>$this->getAddableForm($project)->createView());
    }
    
    /**
     * add user action
     * 
     * This action process to
     * a project user adding
     * with default readable 
     * not writable role.
     * 
     * @param Request $request The current request
     * @param Project $project The current project
     * 
     * @return Response
     */
    public function addUserAction(Request $request, Project $project)
    {
        $form = $this->getAddableForm($project);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $dataArray = $form->getData();
            
            $user = $this->getDoctrine()->getRepository("Cscfa\Bundle\SecurityBundle\Entity\User")->findOneById($dataArray['selected']);
            
            if ($user) {
                $owner = new ProjectOwner();
                $owner->setUser($user)
                    ->setProject($project);
                
                $this->getDoctrine()->getManager()->persist($owner);
                $roles = new ProjectRole();
                $roles->setProperty("all")
                    ->setRead(true)
                    ->setWrite(false);
                $this->getDoctrine()->getManager()->persist($roles);
                $owner->getRoles()->add($roles);
                
                $ownerEvent = new ProjectOwnerEvent($owner, $project, $this->getUser(), "project.event.addOwner");
                $this->get("event_dispatcher")->dispatch("project.event.addOwner", $ownerEvent);
                
                $this->get("project.addOwner.rss.auth")->create($project, $owner);
                
                $this->getDoctrine()->getManager()->flush();
                
                return new Response("done", 200);
            } else {
                return new Response("error", 404);
            }
        } else {
            return new Response("error", 500);
        }
    }
    
    /**
     * Execute role mode
     * 
     * Set up the access mode for
     * a specific role property.
     * 
     * @param ProjectRole $role The role to update
     * @param string      $type The access method (read/write)
     * @param string      $mode The access mode (allow/desallow)
     */
    protected function executeRoleMode(ProjectRole &$role, $type, $mode)
    {
        
        switch ($type) {
            case "read":
                switch ($mode) {
                    case "allow":
                        $role->setRead(true);
                        break;
                    case "desallow":
                        $role->setRead(false);
                }
                break;
            case "write":
                switch ($mode) {
                    case "allow":
                        $role->setWrite(true);
                        break;
                    case "desallow":
                        $role->setWrite(false);
                }
                break;
        }
    }
    
    /**
     * Update user role action.
     * 
     * This action update a
     * user project role.
     * 
     * @param Request $request The current request
     * 
     * @return Response
     */
    public function updateUserRoleAction(Request $request)
    {
        $properties = $request->request->get("property");
        $owner = $request->request->get("owner");
        $action = $request->request->get("action");
        
        $owner = $this->getDoctrine()->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner")->find($owner);
        if (!$owner) {
            return new Response("owner unfound", 404);
        } else {
            list($type, $mode) = explode(".", $action);
            
            foreach ($properties as $property) {
                $projectRole = null;
                foreach ($owner->getRoles() as $role) {
                    if($role->getProperty() === $property){
                        $projectRole = $role;
                        break;
                    }
                }
                
                if ($projectRole === null) {
                    $projectRole = new ProjectRole();
                    $projectRole->setProperty($property);
                    
                    $pre = new ProjectRoleExtension();
                    if ($type == "read") {
                        $inv = $pre->isGrantedProjectAttribute($property, $owner->getProject(), false, true, $owner);
                        $projectRole->setWrite($inv);
                    } else {
                        $inv = $pre->isGrantedProjectAttribute($property, $owner->getProject(), true, false, $owner);
                        $projectRole->setRead($inv);
                    }
                    
                    $this->executeRoleMode($projectRole, $type, $mode);
                    $owner->getRoles()->add($projectRole);
                }else{
                    $this->executeRoleMode($projectRole, $type, $mode);
                }

                $this->getDoctrine()->getManager()->persist($projectRole);
                $this->getDoctrine()->getManager()->persist($owner);
            }
            
            $this->getDoctrine()->getManager()->flush();
                
            $roleEvent = new ProjectRoleEvent($type, $mode, $properties, $owner, $owner->getProject(), $this->getUser(), "project.event.roleUpdate");
            $this->get("event_dispatcher")->dispatch("project.event.roleUpdate", $roleEvent);
            
            $result = ["owner"=>$owner->getId(), "properties"=>$properties, "type"=>$type, "mode"=>($mode=="allow"?true:false)];
            $response = new Response(json_encode($result), 200);
            $response->headers->set("Content-Type", "application/json");
            return $response;
        }
    }
}
