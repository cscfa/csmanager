<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category ChainOfResponsibility
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Chains;

use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Abstracts\AbstractChain;
use Doctrine\ORM\EntityRepository;

/**
 * AddEntityChain.
 *
 * The AddEntityChain
 * process setting of
 * specified property for
 * array or object.
 *
 * Process "specified property" action.
 *
 * Store in named key for array and
 * try set{ucfirst(specified property)}()
 * method, or public property
 * {specified property} before passing
 * responsibility.
 *
 * Retreiv entity itself if it's
 * passed as $options["data"], from
 * the database if it id is passed
 * into $options["data"]. Try to get
 * it from request parameter "specified
 * property" if $options["data"] unexist
 * or if "currentRequest" is passed by.
 *
 * @category ChainOfResponsibility
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddEntityChain extends AbstractChain
{
    /**
     * Property.
     *
     * The property name
     *
     * @var string
     */
    protected $property;

    /**
     * PropertyClass.
     *
     * The property class
     *
     * @var string
     */
    protected $propertyClass;

    /**
     * Routed entity.
     *
     * The route parameter
     * entity
     *
     * @var mixed
     */
    protected $routedEntity;

    /**
     * Entity repository.
     *
     * The entity repository
     *
     * @var EntityRepository
     */
    protected $entityRepository;

    /**
     * Set property.
     *
     * This method allow to set the
     * property to use.
     *
     * @param string $property The property name
     *
     * @return AddEntityChain
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Set property class.
     *
     * This method allow to set the
     * property class to use.
     *
     * @param string $propertyClass The property class
     *
     * @return AddEntityChain
     */
    public function setPropertyClass($propertyClass)
    {
        $this->propertyClass = $propertyClass;

        return $this;
    }

    /**
     * Get entity from repository.
     *
     * This method return the
     * entity basing on it's
     * id from the database, or
     * null.
     *
     * @param string $entityId The entity id
     *
     * @return mixed|null
     */
    protected function getEntityFromRepository($entityId)
    {
        $entity = $this->entityRepository->find($entityId);

        if ($entity) {
            return $entity;
        } else {
            return;
        }
    }

    /**
     * Set entity repository.
     *
     * This method register
     * the entity repository
     *
     * @param EntityRepository $entityRepository The entity repository.
     *
     * @return AddEntityChain
     */
    public function setEntityRepository(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;

        return $this;
    }

    /**
     * Set route entity.
     *
     * This method allow
     * to set the routed
     * entity.
     *
     * @param mixed $entity The routed entity
     *
     * @return AddEntityChain
     */
    public function setRouteEntity($entity = null)
    {
        $this->routedEntity = $entity;

        return $this;
    }

    /**
     * Process.
     *
     * This method process
     * the data.
     *
     * @param mixed $action  The requested action
     * @param mixed $data    The data to process
     * @param array $options The optional data
     *
     * @return ChainOfResponsibilityInterface
     */
    public function process($action, &$data, array $options = array())
    {
        $state = false;

        if ($this->support($action)) {
            $entity = null;

            // Find the entity
            if (array_key_exists('data', $options)) {
                if (is_object($options['data']) && $options['data'] instanceof $this->propertyClass) {
                    $entity = $options['data'];
                } elseif (is_string($options['data'])) {
                    if ($options['data'] == 'currentRequest') {
                        $entity = $this->routedEntity;
                    } else {
                        $entity = $this->getEntityFromRepository($options['data']);
                    }
                }
            } else {
                $entity = $this->routedEntity;
            }

            // Inject it
            if ($entity !== null) {
                if (is_array($data)) {
                    $state = true;
                    $data[$this->property] = $entity;
                } elseif (is_object($data)) {
                    if (in_array('set'.ucfirst($this->property), get_class_methods($data))) {
                        $state = true;
                        $data->{'set'.ucfirst($this->property)}($entity);
                    } elseif (property_exists($data, $this->property)) {
                        $propertyReflection = new \ReflectionProperty($data, $this->property);
                        if ($propertyReflection->isPublic()) {
                            $data->{$this->property} = $entity;
                        }
                    }
                }
            }
        }

        $this->notifyAll(array('state' => $state));

        if ($this->getNext() !== null) {
            return $this->getNext()->process($action, $data, $options);
        } else {
            return $this;
        }
    }

    /**
     * Support.
     *
     * This method check if
     * the current chained
     * instance support the
     * given action.
     *
     * @param mixed $action The action
     *
     * @return bool
     */
    public function support($action)
    {
        return $action == $this->property;
    }

    /**
     * Get action.
     *
     * This method return the
     * action performed by the
     * current chain.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->property;
    }
}
