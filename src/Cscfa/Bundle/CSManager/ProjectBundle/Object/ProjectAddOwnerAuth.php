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
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\SecurityBundle\Entity\User;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner;

/**
 * ProjectAddOwnerAuth class.
 *
 * The ProjectAddOwnerAuth implement
 * access method to project add owner
 * rss system.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ProjectAddOwnerAuth extends RssItemAuthBase
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

            $query = $this->doctrine->getManager()->createQuery(
                "SELECT o
                FROM Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project p
                JOIN Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectOwner o
                WHERE p.id = :project
                AND o.user = :user
                "
            )->setParameter('project', $projectId)
            ->setParameter('user', $user->getId());

            try {
                $result = $query->getSingleResult();

                if ($result) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
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
        return 'project.addOwner.rss.auth';
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
    public function create(Project $project, ProjectOwner $owner)
    {
        $rss = new RssItem(
            'project.event.addOwner',
            sprintf('a new owner was added', $project->getName()),
            $this->router->generate('cscfa_cs_manager_project_home'),
            sprintf(
                'The user %s was added as owner to the project %s.',
                $owner->getUser()->getUsername(),
                $project->getName()
            ),
            null,
            'Project',
            $this,
            array('project' => $project->getId(), 'owner' => $owner->getId())
        );

        $this->doctrine->getManager()->persist($rss);
        $this->doctrine->getManager()->flush();
    }
}
