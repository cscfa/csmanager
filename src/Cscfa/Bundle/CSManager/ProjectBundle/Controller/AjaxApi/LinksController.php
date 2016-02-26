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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectLink;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LinksController class.
 *
 * The LinksController implement
 * access method to project links
 * form into an ajax context.
 *
 * @category Controller
 * @package CscfaCSManagerProjectBundle
 * @author Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license http://opensource.org/licenses/MIT MIT
 * @link http://cscfa.fr
 */
class LinksController extends Controller
{
    /**
     * LinksController attribute
     *
     * This attribute contain the
     * translation domain for this
     * controller.
     *
     * @var string
     */
    protected $transDomain = "CscfaCSManagerProjectBundle_controller_AjaxApi_Links";
    
    /**
     * addSource action
     *
     * This action return the
     * link adding form for
     * ajax context.
     *
     * @param Project $project The current project
     *            
     * @template
     */
    public function addSourceAction(Project $project)
    {
        $link = new ProjectLink();
        $form = $this->createFormBuilder( $link )->setAction( $this->generateUrl( "cscfa_cs_manager_project_ajaxTarget_link_add", array (
            "project"=> $project->getId() 
        ) ) )->add( "link", "text", array (
            "label"=> $this->get( "translator" )->trans( "addSource.link.label", [], $this->transDomain ),
            "attr"=> array (
                "class"=> "form-control" ,
                "placeholder"=> $this->get( "translator" )->trans( "addSource.link.placeholder", [], $this->transDomain )
            ) 
        ) )->add( "submit", "submit", array (
            "label"=> $this->get( "translator" )->trans( "addSource.submit.label", [], $this->transDomain ),
            "attr"=> array (
                "class"=> "btn btn-success" 
            ) 
        ) )->getForm();
        
        return array (
            "form"=> $form->createView() 
        );
    }
    
    /**
     * addTarget action
     *
     * This action process the
     * link adding action into
     * an ajax context.
     *
     * @param Project $project The current project
     *            
     * @return Response
     */
    public function addTargetAction(Request $request, Project $project)
    {
        $link = new ProjectLink();
        $form = $this->createFormBuilder( $link )->add( "link", "text" )->getForm();
        
        $form->handleRequest( $request );
        if ($form->isValid()) {
            $project->getLinks()->add( $link );
            $link->setProject( $project );
            
            $this->getDoctrine()->getManager()->persist( $link );
            $this->getDoctrine()->getManager()->persist( $project );
            $this->getDoctrine()->getManager()->flush();
            
            return new Response( "done", 200 );
        } else {
            return new Response( "error", 500 );
        }
    }
    
    /**
     * removeSource action
     *
     * This action return the
     * link removing confirm
     * form.
     *
     * @param Project $project  The current project
     * @param ProjectLink $link The current link
     *            
     * @return FormView[]
     *            
     * @template
     */
    public function removeSourceAction(Project $project, ProjectLink $link)
    {
        $form = $this->createFormBuilder( array () )->setAction( $this->generateUrl( "cscfa_cs_manager_project_ajaxTarget_link_remove", array (
            "project"=> $project->getId(),
            "link"=> $link->getId() 
        ) ) )->add( "submit", "submit", array (
            "label"=> $this->get( "translator" )->trans( "removeSource.submit.label", [], $this->transDomain ),
            "attr"=> array (
                "class"=> "btn btn-danger" 
            ) 
        ) )->getForm();
        
        return array (
            "form"=> $form->createView(),
            "link"=>$link,
            "project"=>$project
        );
    }
    
    /**
     * removeTarget action
     *
     * This action process the
     * link removing action.
     *
     * @param Project $project  The current project
     * @param ProjectLink $link The link to remove
     *            
     * @return Response
     */
    public function removeTargetAction(Project $project, ProjectLink $link)
    {
        $project->getLinks()->removeElement( $link );
        
        $this->getDoctrine()->getManager()->persist( $project );
        $this->getDoctrine()->getManager()->remove( $link );
        $this->getDoctrine()->getManager()->flush();
        
        return new Response( "done", 200 );
    }
}
