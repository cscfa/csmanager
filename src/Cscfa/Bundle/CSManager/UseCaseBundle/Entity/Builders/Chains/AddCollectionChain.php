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

/**
 * AddCollectionChain.
 *
 * The AddCollectionChain process setting of
 * "specified property" property for
 * array or object.
 *
 * Process "specified property" action.
 *
 * Store in named key for array and try
 * get{ucfirst("specified property")}()->add()
 * method, or public property "specified
 * property"->add() before passing responsibility.
 *
 * @category ChainOfResponsibility
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddCollectionChain extends AbstractChain
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
     * Set property.
     *
     * This method allow to set the
     * property to use.
     *
     * @param string $property The property name
     *
     * @return AddCollectionChain
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Inject.
     *
     * This method inject
     * the data into the
     * collection
     *
     * @param mixed $data       The data to process
     * @param mixed $injectable The data to inject
     */
    protected function inject(&$data, $injectable)
    {
        if (is_array($data)) {
            if (!array_key_exists($this->property, $data) || !is_array($data[$this->property])) {
                $data[$this->property] = array();
            }
            array_push($data[$this->property], $injectable);
        } elseif (is_object($data)) {
            if (in_array('get'.ucfirst($this->property), get_class_methods($data))) {
                if (in_array('add', get_class_methods($data->{'get'.ucfirst($this->property)}()))) {
                    $data->{'get'.ucfirst($this->property)}()->add($injectable);
                }
            } elseif (property_exists($data, $this->property)) {
                $propertyReflection = new \ReflectionProperty($data, $this->property);
                if ($propertyReflection->isPublic()) {
                    $data->{$this->property}->add($injectable);
                }
            }
        }
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

        if ($this->support($action) && array_key_exists('data', $options)) {
            if (is_array($options['data'])) {
                foreach ($options['data'] as $injectable) {
                    $state = true;
                    $this->inject($data, $injectable);
                }
            } else {
                $state = true;
                $this->inject($data, $options['data']);
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
