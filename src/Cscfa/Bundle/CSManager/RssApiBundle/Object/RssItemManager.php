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

namespace Cscfa\Bundle\CSManager\RssApiBundle\Object;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel;
use Doctrine\ORM\QueryBuilder;
use Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem;
use Cscfa\Bundle\CSManager\RssApiBundle\Interfaces\RssItemAuthInterface;
use Cscfa\Bundle\SecurityBundle\Entity\User;

/**
 * RssItemManager class.
 *
 * The RssItemManager implement
 * access to rss items.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class RssItemManager
{
    /**
     * Container.
     *
     * The container service
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Doctrine.
     *
     * The registry service
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Set container.
     *
     * This method allow to
     * set the service container.
     *
     * @param ContainerInterface $container The service container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Set doctrine.
     *
     * This method allow to
     * set the current doctrine
     * service.
     *
     * @param Registry $doctrine The doctrine service
     */
    public function setDoctrine(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get items.
     *
     * This method return a set
     * of authorized items.
     *
     * @param Channel $channel The requested channel
     * @param User    $user    The current user
     *
     * @return array
     */
    public function getItems(Channel $channel, User $user)
    {
        $entityManager = $this->doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        if ($queryBuilder instanceof QueryBuilder) {
            $queryBuilder->select('i')
                ->from("Cscfa\Bundle\CSManager\RssApiBundle\Entity\RssItem", 'i');

            foreach ($channel->getItemTypes() as $key => $type) {
                if ($key == 0) {
                    $queryBuilder->where('i.type = :type'.$key);
                } else {
                    $queryBuilder->orWhere('i.type = :type'.$key);
                }

                $queryBuilder->setParameter('type'.$key, $type);
            }
        }

        $items = $queryBuilder->getQuery()->getResult();

        foreach ($items as $key => $item) {
            if ($item instanceof RssItem) {
                $itemInfo = $item->getAuthService();

                try {
                    $serviceAuth = $this->container->get($itemInfo->getName());

                    if ($serviceAuth instanceof RssItemAuthInterface) {
                        if (!$serviceAuth->isAuthorized($item, $user)) {
                            unset($items[$key]);
                        }
                    }
                } catch (\Exception $e) {
                    unset($items[$key]);
                }
            }
        }

        return $items;
    }
}
