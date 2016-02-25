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
namespace Cscfa\Bundle\CSManager\ProjectBundle\Controller\AjaxApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * ProjectController class.
 *
 * The ProjectController implement
 * access method to project form
 * into an ajax context.
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
     * ProjectController attribute
     * 
     * This attribute contain the
     * translation domain for this
     * controller.
     * 
     * @var string
     */
    protected $transDomain = "CscfaCSManagerProjectBundle_controller_AjaxApi_Project";
    
    /**
     * Get source
     * 
     * This method return an
     * array to use into source
     * template.
     * 
     * @param Project $project      The project to use into the form
     * @param string  $actionUrl    The target url of the form
     * @param string  $propertyName The project property
     * @param string  $type         The project property form type
     * 
     * @return \Symfony\Component\Form\FormView[]
     */
    protected function getSource(Project $project, $actionUrl, $propertyName, $type = "text")
    {
        $form = $this->createFormBuilder($project);
        $form->setAction($this->generateUrl($actionUrl, array("project"=>$project->getId())))
        ->add(
            $propertyName, 
            $type,
            array(
                "label"=>$this->get("translator")->trans($propertyName."Source.".$propertyName.".label", [], $this->transDomain)
            )
        )->add(
            "submit",
            "submit",
            array(
                "label"=>$this->get("translator")->trans($propertyName."Source.submit.label", [], $this->transDomain),
                "attr"=>array(
                    "class"=>"btn btn-success"
                )
            )
        );
        return array("form"=>$form->getForm()->createView());
    }
    
    /**
     * nameSource action
     * 
     * This action return the
     * template for the ajax
     * context name form.
     * 
     * @template
     */
    public function nameSourceAction(Project $project)
    {
        return $this->getSource($project, "cscfa_cs_manager_project_ajaxTarget_project_name", "name");
    }
    
    /**
     * nameTarget action
     * 
     * This action process the
     * project name update.
     * 
     * @param Project $project
     */
    public function nameTargetAction(Request $request, Project $project)
    {
        $builder = $this->createFormBuilder($project);
        $form = $builder->add("name", "text")->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($project);
            
            try{
                $this->getDoctrine()->getManager()->flush();
            } catch(UniqueConstraintViolationException $e) {
                return new Response("<p style='text-align: center;'>This name already exist</p>", 500);
            }
            
            return new Response($project->getName(), 200);
        } else {
            return new Response("error", 500);
        }
    }
    
    /**
     * summarySource action
     * 
     * This action return the
     * template for the ajax
     * context summary form.
     * 
     * @template
     */
    public function summarySourceAction(Project $project)
    {
        return $this->getSource($project, "cscfa_cs_manager_project_ajaxTarget_project_summary", "summary", "textarea");
    }
    
    /**
     * summaryTarget action
     * 
     * This action process the
     * project summary update.
     * 
     * @param Project $project
     */
    public function summaryTargetAction(Request $request, Project $project)
    {
        $builder = $this->createFormBuilder($project);
        $form = $builder->add("summary", "textarea")->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($project);
            
            $this->getDoctrine()->getManager()->flush();
            
            return new Response($project->getSummary(), 200);
        } else {
            return new Response("error", 500);
        }
    }
    
    /**
     * statusSource action
     * 
     * This action return the
     * template for the ajax
     * context status form.
     * 
     * @template
     */
    public function statusSourceAction(Project $project)
    {
        $form = $this->createFormBuilder($project);
        $form->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_project_status", array("project"=>$project->getId())))
        ->add("status", "entity", array(
            "class"=>"Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectStatus",
            "label"=>$this->get("translator")->trans("statusSource.status.label", [], $this->transDomain),
            "choice_label"=>"name",
            'attr' => array(
                'class' => 'form-control'
            )
        ))->add(
            "submit",
            "submit",
            array(
                "label"=>$this->get("translator")->trans("statusSource.submit.label", [], $this->transDomain),
                "attr"=>array(
                    "class"=>"btn btn-success"
                )
            )
        );
        return array("form"=>$form->getForm()->createView());
    }
    
    /**
     * statusTarget action
     * 
     * This action process the
     * project status update.
     * 
     * @param Project $project
     */
    public function statusTargetAction(Request $request, Project $project)
    {
        $builder = $this->createFormBuilder($project);
        $form = $builder->add("status", "entity", array(
            "class"=>"Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectStatus"
        ))->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($project);
            
            $this->getDoctrine()->getManager()->flush();
            
            return new Response($project->getStatus()->getName(), 200);
        } else {
            return new Response("<p style='text-align: center;'>Choose an existing state</p>", 500);
        }
    }
}
