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

/**
 * ChainedBuilderInterface.
 *
 * The ChainedBuilderInterface is used to define
 * the methods of a chained builder.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ChainedBuilderInterface extends BuilderInterface
{
    /**
     * Add chain.
     *
     * This method allow to register
     * a BuilderChainInterface.
     *
     * @param BuilderChainInterface $chain The chain element
     *
     * @return ChainedBuilderInterface
     */
    public function addChain(BuilderChainInterface $chain);
}
