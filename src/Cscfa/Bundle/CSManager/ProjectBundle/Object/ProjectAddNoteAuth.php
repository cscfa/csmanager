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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\Object;

use Cscfa\Bundle\CSManager\RssApiBundle\Object\RssItemAuthBase;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectNote;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Cscfa\Bundle\CSManager\ProjectBundle\Twig\Extension\ProjectRoleExtension;

/**
 * ProjectAddNoteAuth class.
 *
 * The ProjectAddNoteAuth implement
 * access method to project adding
 * note rss system.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectAddNoteAuth extends RssItemAuthBase
{
    /**
     * Doctrine.
     *
     * This property store
     * the doctrine service
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Router.
     *
     * This property store
     * the router service
     *
     * @var Router
     */
    protected $router;

    /**
     * The role extension.
     *
     * The project role
     * extension service
     *
     * @var ProjectRoleExtension
     */
    protected $roleExtension;

    /**
     * Set registry.
     *
     * This method allow
     * to set the doctrine
     * registry service.
     *
     * @param Registry $doctrine
     */
    public function setRegistry(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Set router.
     *
     * This method allow to
     * set the router service.
     *
     * @param Router $router The router service
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Set role extension.
     *
     * This method allow to
     * set the role extension
     * service.
     *
     * @param ProjectRoleExtension $router The router service
     */
    public function setRoleExtension(ProjectRoleExtension $roleExtension)
    {
        $this->roleExtension = $roleExtension;
    }

    /**
     * Is authorized.
     *
     * Check if an item is
     * currently allowed to
     * be showed by the current
     * user.
     *
     * @param RssItem $item The rss item
     * @param User    $user The current user
     *
     * @return bool
     */
    public function isAuthorized(RssItem $item, User $user)
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        } else {
            $extra = $item->getAuthService()->getExtra();
            $projectId = $extra['project'];
            $project = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project")
                ->find($projectId);
            $ownerRepo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner");
            $owner = $ownerRepo->findOneBy(array('project' => $projectId, 'user' => $user->getId()));

            return $this->roleExtension->isGrantedProjectAttribute('notes', $project, true, false, $owner);
        }

        return false;
    }

    /**
     * Get name.
     *
     * Return the service
     * name
     *
     * @return string
     */
    public function getName()
    {
        return 'project.addNote.rss.auth';
    }

    /**
     * Create.
     *
     * This method create
     * a new RssItem into
     * the database.
     *
     * @param Project $project The current project
     */
    public function create(Project $project, ProjectNote $note)
    {
        $rss = new RssItem(
            'project.event.addNote',
            sprintf('a new project note was created', $project->getName()),
            $this->router->generate('cscfa_cs_manager_project_home'),
            sprintf('A new note was added into the project %s. Note : %s', $project->getName(), $note->getContent()),
            null,
            'Project',
            $this,
            array('project' => $project->getId(), 'note' => $note->getId())
        );

        $this->doctrine->getManager()->persist($rss);
        $this->doctrine->getManager()->flush();
    }
}
