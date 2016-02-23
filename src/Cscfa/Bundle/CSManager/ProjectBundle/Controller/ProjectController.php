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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectRole;
use Cscfa\Bundle\DataGridBundle\Objects\DataGridContainer;
use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;
use Cscfa\Bundle\DataGridBundle\Objects\DataGridPaginator;
use Cscfa\Bundle\CSManager\CoreBundle\BootstrapStepper\PaginatorStepper;
use Cscfa\Bundle\DataGridBundle\Objects\PaginatorLimitForm;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectController class.
 *
 * The ProjectController implement
 * access method to project system.
 *
 * @category Controller
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ProjectController extends Controller
{
    /**
     * Project index action
     * 
     * This action render the
     * main project index page
     * 
     * @Template
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * Project view action
     * 
     * This action render the selected 
     * project view for ajax request.
     * 
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function selectProjectAction(Request $request, Project $id)
    {
        return array("project"=>$id);
    }
    
    /**
     * View limit action
     * 
     * This action allow to change
     * the provect view datagrid
     * limit.
     * 
     * @param Request $request The http request
     * 
     * @return Response
     */
    public function viewLimitAction(Request $request)
    {
        $plf = new PaginatorLimitForm();
        $plf->setAllowedLimits(array(5, 25, 100));
        $createForm = $this->createForm("paginatorLimit", $plf);
        
        if ($request->getMethod() === "POST") {
            $createForm->handleRequest($request);

            $choice = $plf->getLimit();
            $value = $plf->getAllowedLimits()[$choice];
            
            $lastLimit = $plf->getLastLimit();
            $page = $plf->getPage();
            
            $page = intval(floor(($page-1) * $lastLimit / $value))+1;
            
            return $this->forward("CscfaCSManagerProjectBundle:Project:viewProject", array("page"=>$page, "limit"=>$value));
        }
    }
    
    /**
     * Project view action
     * 
     * This action render the all project
     * view form for ajax request.
     * 
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function viewProjectAction($page, $limit) {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project");
        
        if($this->isGranted("ROLE_ADMIN")){
            $projects = $repository->findExistant();
        } else {
            $projects = $repository->findReadable($this->getUser()->getId());
        }
        
        $translator = $this->get('translator');
        $domain = "CscfaCSManagerProjectBundle_controller_ProjectController_viewProjectAction";
        $name = $translator->trans('nameHeader', [], $domain);
        $summary = $translator->trans('summaryHeader', [], $domain);

        $paginator = (new DataGridPaginator($projects, $page, $limit))
            ->setStepper($this->container->get("cscfa.view_project.paginator_stepper"))
            ->setAllowedLimits(array(5, 25, 100));
        $data = (new DataGridContainer($paginator->getPageData()))
            ->setAccessMethods(array("getName", "getSummary"))
            ->setHeader(array($name, $summary))
            ->setType(DataGridContainer::TYPE_OBJECT)
            ->setStepper($this->get("cscfa.view_project.datagrid_stepper"));
        
        return array("data"=>$data, "pager"=>$paginator);
    }
    
    /**
     * Project creating form action
     * 
     * This action render the creating
     * project form for ajax request.
     * 
     * @Template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createProjectAction(Request $request){

        $project = new Project();
        $createForm = $this->createForm("createProject", $project, array("action"=>$this->generateUrl('cscfa_cs_manager_project_create_project')));

        $createForm->handleRequest($request);
        
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            
            $projectRole = new ProjectRole();
            $projectRole->setProperty("all");
            $projectRole->setRead(true);
            $projectRole->setWrite(true);
            $manager->persist($projectRole);
            
            $projectOwner = new ProjectOwner();
            $projectOwner->setUser($this->getUser());
            $projectOwner->setProject($project);
            $projectOwner->getRoles()->add($projectRole);
            $manager->persist($projectOwner);
            
            $manager->flush();
            
        }
        
        return array("form"=>$createForm->createView());
    }
}
