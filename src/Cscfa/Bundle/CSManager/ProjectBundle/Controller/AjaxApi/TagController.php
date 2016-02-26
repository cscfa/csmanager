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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectTag;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TagController class.
 *
 * The TagController implement
 * access method to project tag 
 * form into an ajax context.
 *
 * @category Controller
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class TagController extends Controller 
{
    /**
     * TagController attribute
     * 
     * This attribute contain the
     * translation domain for this
     * controller.
     * 
     * @var string
     */
    protected $transDomain = "CscfaCSManagerProjectBundle_controller_AjaxApi_Tag";
    
    /**
     * tagSource action
     * 
     * This action return the
     * template for the ajax
     * context tag form.
     * 
     * @template
     */
    public function tagSourceAction(Project $project)
    {
        $formSelect = $this->createFormBuilder(array());
        $formSelect->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_tag_tag", array("project"=>$project->getId())))
            ->add(
                "tag", 
                "entity", 
                array(
                    "class"=>"Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectTag",
                    "choice_label"=>"name",
                    "label"=>$this->get("translator")->trans("tagSource.selectTagForm.tag.label", [], $this->transDomain),
                    "attr"=>array(
                            "class"=>"form-control"
                    )
                )
            )
            ->add(
                "submit", 
                "submit",
                array(
                    "label"=>$this->get("translator")->trans("tagSource.selectTagForm.submit.label", [], $this->transDomain),
                    "attr"=>array(
                            "class"=>"btn btn-success"
                    )
                )
            );
        
        return array("formSelect"=>$formSelect->getForm()->createView());
    }
    
    /**
     * tagTarget action
     * 
     * This action perform
     * the project tag adding.
     * 
     * @param Request $request The current http request
     * @param Project $project The current Project
     * 
     * @return Response
     */
    public function tagTargetAction(Request $request, Project $project)
    {
        $tag = array();
        $form = $this->createFormBuilder($tag)
            ->add(
                "tag", 
                "entity", 
                array(
                    "class"=>"Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectTag", 
                    "choice_label"=>"name"
                )
            )
            ->getForm();
        
        $form->handleRequest($request);
        $tag = $form->getData();
        
        if ($form->isValid()) {
            if (!in_array($tag["tag"], $project->getTags()->toArray())) {
                $project->getTags()->add($tag["tag"]);
                $this->getDoctrine()->getManager()->persist($project);
            }
            
            $this->getDoctrine()->getManager()->flush();
            return new Response("done", 200);
        } else {
            return new Response("error", 500);
        }
    }

    /**
     * removeTag action
     *
     * This action return the
     * template for the ajax
     * context tag remove form.
     *
     * @template
     */
    public function removeTagSourceAction(Project $project, ProjectTag $tag)
    {
        $confirm = array();
        $form = $this->createFormBuilder($confirm)
            ->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_tag_remove", ["project"=>$project->getId(), "tag"=>$tag->getId()]))
            ->add(
                "submit", 
                "submit",
                array(
                    "label"=>$this->get("translator")->trans("tagSource.removeTagForm.submit.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"btn btn-danger"
                    )
                )
            )
            ->getForm();
        
        return array("form"=>$form->createView(), "project"=>$project, "tag"=>$tag);
    }

    /**
     * removeTagTarget action
     *
     * This action perform
     * the project tag removing.
     *
     * @param Project    $project The current Project
     * @param ProjectTag $tag     The tag to remove
     *
     * @return Response
     */
    public function removeTagTargetAction(Project $project, ProjectTag $tag)
    {
        $project->getTags()->removeElement($tag);
        $tag->getProject()->removeElement($project);

        $this->getDoctrine()->getManager()->persist($project);
        $this->getDoctrine()->getManager()->persist($tag);

        $this->getDoctrine()->getManager()->flush();
        
        return new Response("done", 200);
    }
}
