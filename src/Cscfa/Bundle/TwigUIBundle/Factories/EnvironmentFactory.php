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
    protected function getEmpty()
    {
        return new EnvironmentContainer();
    }

    protected function addObjectsContainer(EnvironmentContainer $container)
    {
        $container->setObjectsContainer(new ObjectsContainer());

        return $this;
    }

    protected function addControllerInfo(EnvironmentContainer $container)
    {
        $container->setControllerInfo(new ControllerInfo());

        return $this;
    }

    protected function addTwigRequests(EnvironmentContainer $container)
    {
        $container->setTwigRequests(new TwigRequestIterator());

        return $this;
    }

    public function getInstance()
    {
        $instance = $this->getInstance();
        $this->addObjectsContainer($instance)
            ->addTwigRequests($instance)
            ->addControllerInfo($instance);

        return $instance;
    }
}
