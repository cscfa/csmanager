<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Builders\Interfaces;

use Cscfa\Bundle\TwigUIBundle\ChainOfResponsibility\Interfaces\ChainInterface;

/**
 * BuilderChainInterface.
 *
 * The BuilderChainInterface is used to define the
 * chain of responsibility methods in a building
 * context.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface BuilderChainInterface extends ChainInterface
{
    /**
     * Build.
     *
     * This method process the building
     * method of the chain element.
     *
     * @param string $property The property to build
     * @param mixed  $data     The data to inject
     * @param array  $object   The object to build
     *
     * @return BuilderChainInterface
     */
    public function build($property, $data, &$object);

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
    public function support($property);
}
