<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\FunctionalTest\Controller;

use Cscfa\Bundle\TwigUIBundle\Controller\ModulableController;

/**
 * EnvController.
 *
 * The EnvController is used to test
 * the EnvironmentalController.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class EnvController extends ModulableController
{
    /**
     * Process action.
     *
     * This method allow to process
     * the test of the module rendering
     * against the ModulableController.
     *
     * @return Response
     */
    public function processAction()
    {
        return $this->processModule(
            'CscfaTwigUIBundle:test:controllerResult.html.twig',
            __METHOD__,
            'cscfa_twig_ui.test.main'
        );
    }
}
