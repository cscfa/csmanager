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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
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
use Cscfa\Bundle\DataGridBundle\Objects\DataGridPaginator;
use Cscfa\Bundle\DataGridBundle\Objects\PaginatorLimitForm;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Cscfa\Bundle\CSManager\ProjectBundle\Event\ProjectBaseEvent;
use Cscfa\Bundle\ToolboxBundle\Strings\StringTool;

/**
 * ProjectController class.
 *
 * The ProjectController implement
 * access method to project system.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectController extends Controller
{
    /**
     * Project index action.
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
     * Project view action.
     *
     * This action render the selected
     * project view for ajax request.
     *
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function selectProjectAction(Project $project)
    {
        return array('project' => $project);
    }

    /**
     * View limit action.
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
        $createForm = $this->createForm('paginatorLimit', $plf);

        if ($request->getMethod() === 'POST') {
            $createForm->handleRequest($request);

            $choice = $plf->getLimit();
            $value = $plf->getAllowedLimits()[$choice];

            $lastLimit = $plf->getLastLimit();
            $page = $plf->getPage();

            $page = intval(floor(($page - 1) * $lastLimit / $value)) + 1;

            return $this->forward(
                'CscfaCSManagerProjectBundle:Project:viewProject',
                array('page' => $page, 'limit' => $value)
            );
        }
    }

    /**
     * Project view action.
     *
     * This action render the all project
     * view form for ajax request.
     *
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function viewProjectAction($page, $limit)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project");

        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $repository->findExistant();
        } else {
            $projects = $repository->findReadable($this->getUser()->getId());
        }

        foreach ($projects as $project) {
            $project->setSummary(StringTool::limitLength($project->getSummary(), 75, '...'));
        }

        $translator = $this->get('translator');
        $domain = 'CscfaCSManagerProjectBundle_controller_ProjectController_viewProjectAction';
        $name = $translator->trans('nameHeader', [], $domain);
        $summary = $translator->trans('summaryHeader', [], $domain);
        $status = $translator->trans('statusHeader', [], $domain);

        $paginator = (new DataGridPaginator($projects, $page, $limit))
            ->setStepper($this->container->get('cscfa.view_project.paginator_stepper'))
            ->setAllowedLimits(array(5, 25, 100));
        $data = (new DataGridContainer($paginator->getPageData()))
            ->setAccessMethods(array('getName', 'getSummary', 'getStatus.getName'))
            ->setHeader(array($name, $summary, $status))
            ->setType(DataGridContainer::TYPE_OBJECT)
            ->setStepper($this->get('cscfa.view_project.datagrid_stepper'));

        return array('data' => $data, 'pager' => $paginator);
    }

    /**
     * Project creating form action.
     *
     * This action render the creating
     * project form for ajax request.
     *
     * @Template
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createProjectAction(Request $request)
    {
        $project = new Project();
        $createForm = $this->createForm(
            'createProject',
            $project,
            array('action' => $this->generateUrl('cscfa_cs_manager_project_create_project'))
        );

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);

            $projectRole = new ProjectRole();
            $projectRole->setProperty('all');
            $projectRole->setRead(true);
            $projectRole->setWrite(true);
            $manager->persist($projectRole);

            $projectOwner = new ProjectOwner();
            $projectOwner->setUser($this->getUser());
            $projectOwner->setProject($project);
            $projectOwner->getRoles()->add($projectRole);
            $manager->persist($projectOwner);

            try {
                $manager->flush();

                $creationEvent = new ProjectBaseEvent($project, $this->getUser(), 'project.event.created');
                $this->get('event_dispatcher')->dispatch('project.event.created', $creationEvent);

                $this->get('project.created.rss.auth')->create($project);

                return $this->redirect(
                    $this->generateUrl(
                        'cscfa_cs_manager_project_select_project',
                        array('project' => $project->getId())
                    ),
                    302
                );
            } catch (UniqueConstraintViolationException $e) {
                $createForm->get('name')->addError(new FormError('This name already exist'));

                return $this->render(
                    'CscfaCSManagerProjectBundle:Project:createProject.html.twig',
                    array('form' => $createForm->createView())
                );
            }
        }

        return $this->render(
            'CscfaCSManagerProjectBundle:Project:createProject.html.twig',
            array('form' => $createForm->createView())
        );
    }

    /**
     * Remove project action.
     *
     * This action return the
     * project removing
     * template.
     *
     * @param Project $project The current project
     *
     * @return Response
     */
    public function removeProjectAction(Request $request, Project $project)
    {
        $translator = $this->get('translator');
        $domain = 'CscfaCSManagerProjectBundle_controller_ProjectController_viewProjectAction';

        $form = $this->createFormBuilder($project)
            ->setAction(
                $this->generateUrl(
                    'cscfa_cs_manager_project_remove_project',
                    array('project' => $project->getId())
                )
            )->add(
                'deleted',
                'checkbox',
                array(
                    'label' => $translator->trans('removeProject.deleted.label', [], $domain),
                    'required' => false,
                )
            )
            ->add(
                'submit',
                'submit',
                array(
                    'label' => $translator->trans('removeProject.submit.label', [], $domain),
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($project);

                $removeEvent = new ProjectBaseEvent($project, $this->getUser(), 'project.event.removed');
                $this->get('event_dispatcher')->dispatch('project.event.removed', $removeEvent);

                $this->get('project.removed.rss.auth')->create($project);

                $this->getDoctrine()->getManager()->flush();

                if ($project->isDeleted()) {
                    return $this->redirect($this->generateUrl('cscfa_cs_manager_project_view_project'));
                } else {
                    return $this->redirect(
                        $this->generateUrl(
                            'cscfa_cs_manager_project_select_project',
                            array('project' => $project->getId())
                        )
                    );
                }
            }
        }

        return $this->render(
            'CscfaCSManagerProjectBundle:Project:removeProject.html.twig',
            array('form' => $form->createView())
        );
    }
}
