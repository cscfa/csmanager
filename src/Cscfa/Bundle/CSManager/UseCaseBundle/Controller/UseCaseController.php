<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
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

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Controller;

use Cscfa\Bundle\TwigUIBundle\Controller\ModulableController;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\TwigUIBundle\Builders\EnvironmentOptionBuilder;
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;

/**
 * UseCaseController class.
 *
 * The UseCaseController process the actions
 * for the UseCaseBundle.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class UseCaseController extends ModulableController
{
    /**
     * Add action.
     *
     * This method Process the
     * add use case module.
     *
     * @param Request $request The current request
     * @param Project $project The project where adding the UseCase
     *
     * @return Response
     */
    public function addAction(Request $request, Project $project)
    {
        $builder = new EnvironmentOptionBuilder();
        $builder->addOption($builder::OBJECT_CONTAINER_OBJECT, array($request, 'request'))
            ->addOption($builder::OBJECT_CONTAINER_OBJECT, array($project, 'project'));

        return $this->processModule(
            'CscfaCSManagerUseCaseBundle:module:addUseCase.html.twig',
            __METHOD__,
            'addUseCaseModule',
            $builder
        );
    }
}
