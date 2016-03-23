<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Twig extension
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Twig\Extension;

use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequestIterator;

/**
 * TwigModuleExtension class.
 *
 * The TwigModuleExtension class
 * define the twig extension to
 * display the twigUI modules.
 *
 * @category Twig extension
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TwigModuleExtension extends \Twig_Extension
{
    /**
     * Get function.
     *
     * Return the mapping of
     * the function name and
     * class methods
     *
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'twigUIEnvironment',
                array($this, 'processEnvironment'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'twigUIModule',
                array($this, 'processModule'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true,
                )
            ),
        );
    }

    /**
     * Process environment.
     *
     * This method return the precessed
     * result of the module environment.
     *
     * @param \Twig_Environment    $twig              The twig environment
     * @param EnvironmentContainer $moduleEnvironment The module environment
     *
     * @return string
     */
    public function processEnvironment(\Twig_Environment $twig, EnvironmentContainer $moduleEnvironment)
    {
        return $this->processModule($twig, $moduleEnvironment->getTwigRequests());
    }

    /**
     * Process module.
     *
     * This module return the processed
     * result of a module.
     *
     * @param \Twig_Environment   $twig   The twig environment
     * @param TwigRequestIterator $module The module to process
     *
     * @return string
     */
    public function processModule(\Twig_Environment $twig, TwigRequestIterator $module)
    {
        $rendering = '';

        foreach ($module as $moduleName => $request) {
            $rendering .= $this->processRequest($twig, $request, $moduleName);
        }

        return $rendering;
    }

    /**
     * Process request.
     *
     * This method return the processed
     * result of a request.
     *
     * @param \Twig_Environment $twig       The twig environment
     * @param TwigRequest       $request    The twig request
     * @param string            $moduleName The module name
     *
     * @return string
     */
    public function processRequest(\Twig_Environment $twig, TwigRequest $request, $moduleName)
    {
        $clonedRequest = clone $request;

        $clonedRequest->addArgument('moduleName', $moduleName);
        $clonedRequest->addArgument('moduleChilds', $clonedRequest->getChilds());

        $template = $clonedRequest->getTwigPath();
        $context = $clonedRequest->getArguments();

        $rendering = $twig->render($template, $context);

        return $rendering;
    }

    /**
     * Get name.
     *
     * Return the current extension name
     *
     * @see Twig_ExtensionInterface::getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'cs_twig_module_extension';
    }
}
