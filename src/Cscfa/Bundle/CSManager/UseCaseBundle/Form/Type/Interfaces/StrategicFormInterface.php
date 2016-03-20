<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category FormInterface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Type\Interfaces;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Interfaces\UseCaseStrategyInterface;

/**
 * StrategicForm class.
 *
 * The StrategicForm define usage
 * of form with strategy pattern.
 *
 * @category FormInterface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface StrategicFormInterface
{
    /**
     * Set strategy.
     *
     * This method allow to register
     * the create form strategy.
     *
     * @param UseCaseStrategyInterface $strategy The strategy to use
     *
     * @return UseCaseType
     */
    public function setStrategy(UseCaseStrategyInterface $strategy);
}
