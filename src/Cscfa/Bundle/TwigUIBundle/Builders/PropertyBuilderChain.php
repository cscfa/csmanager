<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Builders;

use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderChainInterface;
use Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Abstracts\AbstractChain;

/**
 * PropertyBuilderChain.
 *
 * The PropertyBuilderChain is used to build both
 * objects or array by passing property to set and
 * data.
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class PropertyBuilderChain extends AbstractChain implements BuilderChainInterface
{
    /**
     * Property.
     *
     * This property store the supported
     * property of the current chain.
     *
     * @var string
     */
    protected $property;

    /**
     * Build.
     *
     * This method process the building
     * method of the chain element.
     *
     * Passing the entity to set into
     * $options["data"].
     *
     * @param string $property The property to build
     * @param mixed  $data     The data to inject
     * @param array  $object   The object to build
     *
     * @return BuilderChainInterface
     */
    public function build($property, $data, &$object)
    {
        if ($this->support($property)) {
            if (is_array($object)) {
                $this->injectToArray($data, $object);
            } elseif (is_object($object)) {
                $this->injectToObject($data, $object);
            }
        }

        return $this->toNext($property, $data, $object);
    }

    /**
     * Inject to object.
     *
     * This method inject the data into
     * the current builded object as object.
     *
     * @param mixed $data   The data to inject
     * @param mixed $object The object to build
     */
    protected function injectToObject($data, &$object)
    {
        $reflex = new \ReflectionClass($object);

        $methods = array();
        foreach ($reflex->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methods[] = $method->name;
        }
        $properties = array();
        foreach ($reflex->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[] = $property->name;
        }

        $setters = array(
            array('set'.ucfirst($this->property), $methods, true),
            array('add'.ucfirst($this->property), $methods, true),
            array($this->property, $properties, false),
        );

        foreach ($setters as $setter) {
            if (in_array($setter[0], $setter[1])) {
                if ($setter[2]) {
                    $reflexFunc = new \ReflectionMethod($object, $setter[0]);

                    if ($reflexFunc->getNumberOfParameters() <= 1) {
                        $object->{$setter[0]}($data);
                    } elseif (is_array($data)) {
                        $reflex->getMethod($setter[0])
                            ->invokeArgs($object, $data);
                    }
                } else {
                    $object->{$setter[0]} = $data;
                }
                break;
            }
        }
    }

    /**
     * Inject to array.
     *
     * This method inject the data into
     * the current builded object as array.
     *
     * @param mixed $data   The data to inject
     * @param mixed $object The object to build
     */
    protected function injectToArray($data, &$object)
    {
        $object[$this->property] = $data;
    }

    /**
     * To next.
     *
     * This method pass the arguments to
     * the next chain.
     *
     * @param string $property The property to build
     * @param mixed  $data     The data to inject
     * @param array  $options  The options of the build
     *
     * @return PropertyBuilderChain|BuilderChainInterface
     */
    protected function toNext($property, $data, &$options)
    {
        if ($this->getNext() !== null && $this->getNext() instanceof BuilderChainInterface) {
            return $this->getNext()->build($property, $data, $options);
        } else {
            return $this;
        }
    }

    /**
     * Set property.
     *
     * This method set the property
     * that the chain can build.
     *
     * @param string $property The property
     *
     * @throws \Exception if the property is not a string
     *
     * @return PropertyBuilderChain
     */
    public function setProperty($property)
    {
        if (!is_string($property)) {
            throw new \Exception(
                'The given property must be a string',
                500
            );
        }
        $this->property = $property;

        return $this;
    }

    /**
     * Get property.
     *
     * This method return the property
     * that the chain can build.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Support.
     *
     * This method check if the current chain
     * element support the given property.
     *
     * @param string $property The property to check of
     *
     * @return bool
     */
    public function support($property)
    {
        return $property === $this->property;
    }
}
