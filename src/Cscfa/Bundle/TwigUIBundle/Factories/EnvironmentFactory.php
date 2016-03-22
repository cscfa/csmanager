<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Factories;

use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\ObjectsContainer;
use Cscfa\Bundle\TwigUIBundle\Object\ControllerInformation\ControllerInfo;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\ChainedBuilderInterface;
use Cscfa\Bundle\TwigUIBundle\Object\TwigHierarchy\TwigHierarchy;

/**
 * EnvironmentFactory.
 *
 * The EnvironmentFactory is used to create the
 * EnvironmentContainer instances.
 *
 * @category Factory
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentFactory
{
    /**
     * Objects container builder.
     *
     * This property store the ObjectsContainer
     * builder.
     *
     * @var ChainedBuilderInterface
     */
    protected $ocBuilder;

    /**
     * Controller info builder.
     *
     * This property store the ControllerInfo
     * builder.
     *
     * @var ChainedBuilderInterface
     */
    protected $ciBuilder;

    /**
     * Twig Request Iterator builder.
     *
     * This property store the TwigRequestIterator
     * builder.
     *
     * @var ChainedBuilderInterface
     */
    protected $triBuilder;

    /**
     * Get empty.
     *
     * This method return an empty instance
     * of EnvironmentContainer.
     *
     * @return EnvironmentContainer
     */
    protected function getEmpty()
    {
        return new EnvironmentContainer();
    }

    /**
     * Add object container.
     *
     * This method inject a new instance
     * of ObjectsContainer into an instance
     * of EnvironmentContainer.
     *
     * @param EnvironmentContainer $container The Environment container instance
     *                                        where inject the instance of ObjectsContainer.
     *
     * @return EnvironmentFactory
     */
    protected function addObjectsContainer(EnvironmentContainer $container)
    {
        $container->setObjectsContainer(new ObjectsContainer());

        return $this;
    }

    /**
     * Add controller info.
     *
     * This method inject a new instance
     * of ControllerInfo into an instance
     * of EnvironmentContainer.
     *
     * @param EnvironmentContainer $container The Environment container instance
     *                                        where inject the instance of ControllerInfo.
     *
     * @return EnvironmentFactory
     */
    protected function addControllerInfo(EnvironmentContainer $container)
    {
        $container->setControllerInfo(new ControllerInfo());

        return $this;
    }

    /**
     * Add controller info.
     *
     * This method inject a new instance
     * of TwigRequestIterator into an instance
     * of EnvironmentContainer.
     *
     * @param EnvironmentContainer $container The Environment container instance
     *                                        where inject the instance of TwigRequestIterator.
     *
     * @return EnvironmentFactory
     */
    protected function addTwigRequests(EnvironmentContainer $container)
    {
        $container->setTwigRequests(new TwigRequestIterator());

        if ($container->getTwigHierarchy() !== null) {
            $container->getTwigHierarchy()
                ->setMainRegistry($container->getTwigRequests());
        }

        return $this;
    }

    /**
     * Add twig hierarchy.
     *
     * This method inject a new instance
     * of TwigHierarchy into an instance
     * of EnvironmentContainer.
     *
     * @param EnvironmentContainer $container The Environment container instance
     *                                        where inject the instance of TwigRequestIterator
     *
     * @return EnvironmentFactory
     */
    protected function addTwigHierarchy(EnvironmentContainer $container)
    {
        $container->setTwigHierarchy(new TwigHierarchy());

        if ($container->getTwigRequests() !== null) {
            $container->getTwigHierarchy()
                ->setMainRegistry($container->getTwigRequests());
        }

        return $this;
    }

    /**
     * Get instance.
     *
     * This method return a new instance of EnvironmentContainer with the
     * dependencies already injecteds.
     *
     * The factory use an instance of ChainedBuilder to process ObjectContainer
     * instance building. Give property=>data couple of value into an array,
     * registered into an `ObjectContainer` key to give data to process. Note that
     * the supported property of the ObjectContainer builder is `object`.
     *
     * The factory use an instance of ChainedBuilder to process ControllerInfo
     * instance building. Give property=>data couple of value into an array,
     * registered into an `ControllerInfo` key to give data to process. Note that
     * the supported property of the ControllerInfo builder are `controllerName`
     * and `methodName`.
     *
     * The factory use an instance of ChainedBuilder to process TwigRequestIterator
     * instance building. Give property=>data couple of value into an array,
     * registered into an `TwigRequests` key to give data to process. Note that
     * the supported property of the TwigRequestIterator builder is `twigRequest`.
     *
     * @param array $options The options of the factory build
     *
     * @example <pre style="white-space: pre;">EnvironmentFactory::getInstance(
     * &nbsp; array(
     * &nbsp;&nbsp; 'ObjectContainer'=>array(
     * &nbsp;&nbsp;&nbsp; ['object'=>array(new \stdClass(), "standardClass")]
     * &nbsp;&nbsp; ),
     * &nbsp;&nbsp; 'ControllerInfo'=>array(
     * &nbsp;&nbsp;&nbsp; ['controllerName'=>'myAwesomeController'],
     * &nbsp;&nbsp;&nbsp; ['methodName'=>'foo']
     * &nbsp;&nbsp; ),
     * &nbsp;&nbsp; 'TwigRequests'=>array(
     * &nbsp;&nbsp;&nbsp; ['twigRequest'=>array(new TwigRequest('path', []), 'theRequestAlias')],
     * &nbsp;&nbsp;&nbsp; ['twigRequest'=>array(new TwigRequest('secondPath', []), 'theAlias')]
     * &nbsp;&nbsp; )
     * &nbsp; )
     *  )</pre>
     *
     * @return EnvironmentContainer
     */
    public function getInstance(array $options = array())
    {
        $instance = $this->getEmpty();
        $this->addObjectsContainer($instance)
            ->addTwigRequests($instance)
            ->addControllerInfo($instance)
            ->addTwigHierarchy($instance);

        if (array_key_exists('ObjectContainer', $options)) {
            $this->ocBuilder->setElement($instance->getObjectsContainer());
            foreach ($options['ObjectContainer'] as $element) {
                foreach ($element as $property => $data) {
                    $this->ocBuilder->add($property, $data);
                }
            }
            $instance->setObjectsContainer($this->ocBuilder->getResult());
        }
        if (array_key_exists('ControllerInfo', $options)) {
            $this->ciBuilder->setElement($instance->getControllerInfo());
            foreach ($options['ControllerInfo'] as $element) {
                foreach ($element as $property => $data) {
                    $this->ciBuilder->add($property, $data);
                }
            }
            $instance->setControllerInfo($this->ciBuilder->getResult());
        }
        if (array_key_exists('TwigRequests', $options)) {
            $this->triBuilder->setElement($instance->getTwigRequests());
            foreach ($options['TwigRequests'] as $element) {
                foreach ($element as $property => $data) {
                    $this->triBuilder->add($property, $data);
                }
            }
            $instance->setTwigRequests($this->triBuilder->getResult());
        }

        return $instance;
    }

    /**
     * Set object container builder.
     *
     * This method register an instance of
     * ChainedBuilderInterface configured
     * to build the ObjectContainer instance.
     *
     * @param ChainedBuilderInterface $ocBuilder The ObjectContainer builder
     *
     * @return EnvironmentFactory
     */
    public function setOCBuilder(ChainedBuilderInterface $ocBuilder)
    {
        $this->ocBuilder = $ocBuilder;

        return $this;
    }

    /**
     * Set controller info builder.
     *
     * This method register an instance of
     * ChainedBuilderInterface configured
     * to build the ControllerInfo instance.
     *
     * @param ChainedBuilderInterface $ciBuilder The ControllerInfo builder
     *
     * @return EnvironmentFactory
     */
    public function setCIBuilder(ChainedBuilderInterface $ciBuilder)
    {
        $this->ciBuilder = $ciBuilder;

        return $this;
    }

    /**
     * Set twig request iterator builder.
     *
     * This method register an instance of
     * ChainedBuilderInterface configured
     * to build the TwigRequestIterator
     * instance.
     *
     * @param ChainedBuilderInterface $triBuilder The TwigRequestIterator builder
     *
     * @return EnvironmentFactory
     */
    public function setTRIBuilder(ChainedBuilderInterface $triBuilder)
    {
        $this->triBuilder = $triBuilder;

        return $this;
    }
}
