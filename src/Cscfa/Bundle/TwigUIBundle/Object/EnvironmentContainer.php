<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
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

namespace Cscfa\Bundle\TwigUIBundle\Object;

/**
 * EnvironmentContainer.
 *
 * The EnvironmentContainer is used to store and pass
 * controller environment variables to be used into
 * TwigUI controller composite or each others.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvironmentContainer
{
    /**
     * Objects container.
     *
     * This property store an ObjectsContainer instance
     * that allow a container to store severals objects
     * as process environment elements.
     *
     * @var ObjectsContainer
     */
    protected $objectsContainer;
}
