<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category EntityBuilder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders;

use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Builders\Interfaces\EntityBuilderInterface;
use Cscfa\Bundle\CSManager\UseCaseBundle\ChainOfResponsabilities\Interfaces\ChainOfResponsibilityInterface;

/**
 * DefaultEntityBuilder.
 *
 * The DefaultEntityBuilder
 * process entity building
 * methods.
 *
 * @category EntityBuilder
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class DefaultEntityBuilder implements EntityBuilderInterface
{
    /**
     * Chain.
     *
     * The building chain of
     * responsibility
     *
     * @var ChainOfResponsibilityInterface
     */
    protected $chain;

    /**
     * Entity.
     *
     * The entity to build
     *
     * @var mixed
     */
    protected $entity;

    /**
     * Add.
     *
     * This method add a
     * new property to the
     * entity.
     *
     * @param mixed $property The property to add
     * @param mixed $data     The data to use
     * @param array $options  The building options
     *
     * @return EntityBuilderInterface
     */
    public function add($property, $data = null, array $options = array())
    {
        if ($this->entity !== null && $this->chain !== null) {
            $options['data'] = $data;
            $this->chain->process($property, $this->entity, $options);
        }

        return $this;
    }

    /**
     * Set process chain.
     *
     * This method allow to set
     * the chain of responsibility
     * that perform the adding action.
     *
     * @param ChainOfResponsibilityInterface $chain The chain of responsibility
     *
     * @return EntityBuilderInterface
     */
    public function setProcessChain(ChainOfResponsibilityInterface $chain)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get process chain.
     *
     * This method return the
     * current chain of
     * responsibility.
     *
     * @return ChainOfResponsibilityInterface
     */
    public function getProcessChain()
    {
        return $this->chain;
    }

    /**
     * Get entity.
     *
     * This method return the builded
     * entity.
     *
     * @return mixed $entity The builded entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity.
     *
     * This method allow to set
     * the entity to build.
     *
     * @param mixed $entity The entity to build
     *
     * @return EntityBuilderInterface
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
