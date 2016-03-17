<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Listener
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\TrackBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Cscfa\Bundle\CSManager\TrackBundle\Entity\Tracker;
use Cscfa\Bundle\CSManager\TrackBundle\Entity\TrackerLink;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TrackerListener class.
 *
 * Thist class implements
 * event listeners for the
 * tracker context
 *
 * @category Listener
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 */
abstract class TrackerListener
{
    /**
     * Doctrine.
     *
     * The application doctrine
     * service
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Set arguments.
     *
     * This method initialize
     * the arguments
     *
     * @param Registry $doctrine The doctrine service
     */
    public function setArguments(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get new tracker.
     *
     * This method return a new
     * tracker instance.
     *
     * @return Tracker
     */
    protected function getNewTracker($eventName = null, $message = null, $user = null, $links = null)
    {
        $tracker = new Tracker();
        $tracker->setEventName($eventName)
            ->setMessage($message)
            ->setUser($user)
            ->setLinks($links);
        $this->doctrine->getManager()->persist($tracker);
    }

    /**
     * Get or create link.
     *
     * This method return a link
     * or a new instance of.
     *
     * @param string $className The link classname
     * @param string $linkedId  The link id
     *
     * @return TrackerLink
     */
    protected function getOrCreateLink($className, $linkedId)
    {
        $repo = $this->doctrine->getRepository("Cscfa\Bundle\CSManager\TrackBundle\Entity\TrackerLink");
        $link = $repo->findOneBy(array(
            'className' => $className,
            'linkedId' => $linkedId,
        ));

        if (!$link) {
            $link = new TrackerLink();
            $link->setClassName($className)
                ->setLinkedId($linkedId);

            $this->doctrine->getManager()->persist($link);
        }

        return $link;
    }

    /**
     * Create array collection.
     *
     * This method return an
     * array collection of
     * TrackerLink
     *
     * @param array $entities The entities to link
     *
     * @return ArrayCollection
     */
    protected function createArrayCollection(array $entities = array())
    {
        $arrayCollection = new ArrayCollection();

        foreach ($entities as $entity) {
            if (is_object($entity) && method_exists($entity, 'getId')) {
                $arrayCollection->add(
                    $this->getOrCreateLink(get_class($entity), $entity->getId())
                );
            }
        }

        return $arrayCollection;
    }
}
