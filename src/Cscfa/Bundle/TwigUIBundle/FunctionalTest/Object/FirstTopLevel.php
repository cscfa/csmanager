<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\FunctionalTest\Object;

use Cscfa\Bundle\TwigUIBundle\Modules\AbstractTopLevelModule;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;

/**
 * FirstTopLevel.
 *
 * The FirstTopLevel is used to
 * test the module usage.
 *
 * @category Test
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class FirstTopLevel extends AbstractTopLevelModule
{
    /**
     * Get name.
     *
     * This method return the name
     * of the module.
     *
     * @return string
     */
    public function getName()
    {
        return 'firstTopLevel';
    }

    /**
     * Render.
     *
     * This method run the module
     * process. It return a TwigRequest
     * if needed or null.
     *
     * @param EnvironmentContainer $environment The current environment
     *
     * @return TwigRequest|null
     */
    public function render(EnvironmentContainer $environment)
    {
        $request = new TwigRequest();
        $request->setTwigPath('CscfaTwigUIBundle:test:firstTop.html.twig')
            ->addArgument('arg', 'argument');

        return $request;
    }
}
