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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectNote;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectNoteEvent;

/**
 * NoteController class.
 *
 * The NoteController implement
 * access method to project notes
 * form into an ajax context.
 *
 * @category Controller
 * @package CscfaCSManagerProjectBundle
 * @author Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license http://opensource.org/licenses/MIT MIT
 * @link http://cscfa.fr
 */
class NoteController extends Controller
{
    /**
     * NoteController attribute
     *
     * This attribute contain the
     * translation domain for this
     * controller.
     *
     * @var string
     */
    protected $transDomain = "CscfaCSManagerProjectBundle_controller_AjaxApi_Note";
    
    /**
     * addSource action
     * 
     * This action return the
     * adding note form for
     * the ajax context.
     * 
     * @param Project $project The current project
     * 
     * @return FormView[]
     * @template
     */
    public function addSourceAction(Project $project)
    {
        $note = new ProjectNote();
        $form = $this->createFormBuilder($note)
            ->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_note_add", array("project"=>$project->getId())))
            ->add(
                "content", 
                "textarea",
                array(
                    "label"=>$this->get("translator")->trans("addSource.content.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"form-control cs-width-lock"
                    )
                )
            )
            ->add(
                "submit",
                "submit",
                array(
                    "label"=>$this->get("translator")->trans("addSource.submit.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"btn btn-success"
                    )
                )
            )
            ->getForm();
        
        return array("form"=>$form->createView())
;    }
    
    /**
     * addTarget action
     * 
     * This action process the
     * note adding.
     * 
     * @param Request $request The current request
     * @param Project $project The current project
     * 
     * @return Response
     */
    public function addTargetAction(Request $request, Project $project)
    {
        $note = new ProjectNote();
        $form = $this->createFormBuilder($note)->add("content", "textarea")->getForm();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $note->setUser($this->getUser());
            $note->setProject($project);
            
            $this->getDoctrine()->getManager()->persist($note);
            
            $noteEvent = new ProjectNoteEvent($note, $project, $this->getUser(), "project.event.addNote");
            $this->get("event_dispatcher")->dispatch("project.event.addNote", $noteEvent);
            
            $this->get("project.addNote.rss.auth")->create($project, $note);
            
            $this->getDoctrine()->getManager()->flush();
            
            return new Response("done", 200);
        } else {
            return new Response("error", 500);
        }
    }
    
    /**
     * editSource action
     * 
     * This action return the
     * note edition form.
     * 
     * @param Project     $project The current project
     * @param ProjectNote $note    The current note
     * 
     * @return FormView[]
     * 
     * @template
     */
    public function editSourceAction(Project $project, ProjectNote $note)
    {
        $formUpdate = $this->createFormBuilder($note)
            ->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_note_edit", array("project"=>$project->getId(), "note"=>$note->getId())))
            ->add(
                "content", 
                "textarea", 
                array(
                    "label"=>$this->get("translator")->trans("editSource.content.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"form-control cs-width-lock"
                    )
                )
            )
            ->add(
                "submit", 
                "submit", 
                array(
                    "label"=>$this->get("translator")->trans("editSource.submit.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"btn btn-success"
                    )
                )
            )
            ->getForm();
        
        $formDelete = $this->createFormBuilder([])
            ->setAction($this->generateUrl("cscfa_cs_manager_project_ajaxTarget_note_remove", array("project"=>$project->getId(), "note"=>$note->getId())))
            ->add(
                "submit",
                "submit",
                array(
                    "label"=>$this->get("translator")->trans("removeSource.submit.label", [], $this->transDomain),
                    "attr"=>array(
                        "class"=>"btn btn-danger"
                    )
                )
            )
            ->getForm();
        
        return array("formUpdate"=>$formUpdate->createView(), "formDelete"=>$formDelete->createView());
    }

    /**
     * editTarget action
     * 
     * This action perform a
     * note update action
     * 
     * @param Request     $request The current request
     * @param Project     $project The current project
     * @param ProjectNote $note    The current note
     * 
     * @return Response
     */
    public function editTargetAction(Request $request, Project $project, ProjectNote $note)
    {
        $form = $this->createFormBuilder($note)->add("content", "textarea")->getForm();
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($note);

            $editEvent = new ProjectNoteEvent($note, $project, $this->getUser(), "project.event.editNote");
            $this->get("event_dispatcher")->dispatch("project.event.editNote", $editEvent);
            
            $this->getDoctrine()->getManager()->flush();
            
            return new Response("done", 200);
        } else {
            return new Response("error", 500);
        }
    }
    
    /**
     * removeTarget action
     * 
     * This action perfom a
     * project note deletion.
     * 
     * @param Project     $project The current project
     * @param ProjectNote $note    The current note
     * 
     * @return Response
     */
    public function removeTargetAction(Project $project, ProjectNote $note)
    {
        $note->setDeleted(true);
        $this->getDoctrine()->getManager()->persist($note);

            $removeEvent = new ProjectNoteEvent($note, $project, $this->getUser(), "project.event.remNote");
            $this->get("event_dispatcher")->dispatch("project.event.remNote", $removeEvent);
            
        $this->getDoctrine()->getManager()->flush();
        
        return new Response("done", 200);
    }
}
