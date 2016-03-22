<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Modules;

use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * AbstractStepModule.
 *
 * The AbstractStepModule is used to
 * define the process of the step
 * modules methods.
 *
 * @category Abstract
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class AbstractStepModule extends AbstractModule
{
    /**
     * Constructor.
     *
     * The default AbstractModule
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process.
     *
     * This method process the module by
     * calling the render method.
     *
     * @param EnvironmentContainer $environment The current environment
     *
     * @return ModuleInterface
     *
     * @see \Cscfa\Bundle\TwigUIBundle\Interfaces\ModuleInterface::process()
     */
    public function process(EnvironmentContainer $environment)
    {
        $environment->getTwigHierarchy()->downStep();

        $rendering = $this->render($environment);

        if ($rendering instanceof TwigRequest) {
            $environment->getTwigHierarchy()
                ->register($rendering, $this->getName());
        }

        $this->getModules()->processAll($environment);

        return $this;
    }
}
