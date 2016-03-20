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

use Cscfa\Bundle\TwigUIBundle\Builders\Abstracts\AbstractBuilder;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\ChainedBuilderInterface;
use Cscfa\Bundle\TwigUIBundle\Builders\Interfaces\BuilderChainInterface;

/**
 * ChainedBuilder.
 *
 * The ChainedBuilder is able to process a build
 * by passing arguments to a building chain.
 *
 * @category Builder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ChainedBuilder extends AbstractBuilder implements ChainedBuilderInterface
{
    /**
     * Building chain.
     *
     * This property store @author vallance
     * building chain.
     *
     * @var BuilderChainInterface
     */
    protected $buildingChain;

    /**
     * Add.
     *
     * This method allow the builder to build
     * a part of the register element.
     *
     * @param string $property The property to build
     * @param mixed  $data     The data to inject
     * @param array  $options  The options of the build
     *
     * @return BuilderInterface
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function add($property, $data, array $options = array())
    {
        $this->buildingChain->build($property, $data, $this->element);

        return $this;
    }

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
    public function addChain(BuilderChainInterface $chain)
    {
        if ($this->buildingChain !== null) {
            $this->buildingChain->setNext($chain);
        } else {
            $this->buildingChain = $chain;
        }

        return $this;
    }
}
