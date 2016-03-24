<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Module
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\modules\AddUseCase;

use Cscfa\Bundle\TwigUIBundle\Modules\AbstractTopLevelModule;
use Cscfa\Bundle\TwigUIBundle\Object\EnvironmentContainer;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;

/**
 * AddUseCaseInfoModule.
 *
 * The AddUseCaseInfoModule is a top level
 * module that is in charge of display
 * information about UseCase creation.
 *
 * @category Module
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddUseCaseInfoModule extends AbstractTopLevelModule
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
        return 'AddUseCaseInfoModule';
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
        $objectContainer = $environment->getObjectsContainer();
        if (!$objectContainer->hasObject('project')) {
            return;
        }
        $project = $objectContainer->getObject('project');

        if ($project instanceof Project) {
            $twigRequest = new TwigRequest();
            $twigRequest->setTwigPath('CscfaCSManagerUseCaseBundle:module:AddUseCaseInfoModule.html.twig');
            $twigRequest->addArgument('projectName', $project->getName());

            return $twigRequest;
        } else {
            return;
        }
    }
}
