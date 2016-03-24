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
use Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Factories\UseCaseFormFactory;
use Cscfa\Bundle\TwigUIBundle\Object\TwigRequest\TwigRequest;
use Cscfa\Bundle\CSManager\UseCaseBundle\Entity\UseCase;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

/**
 * AddUseCaseFormModule.
 *
 * The AddUseCaseFormModule is a top level
 * module that is in charge of display
 * an process the add use case form.
 *
 * @category Module
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class AddUseCaseFormModule extends AbstractTopLevelModule
{
    /**
     * Form factory.
     *
     * This property store the form
     * factory for the UseCase.
     *
     * @var UseCaseFormFactory
     */
    protected $formFactory;

    /**
     * Entity manager.
     *
     * This property store the
     * entity manager to register
     * UseCase into the database.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Set entity manager.
     *
     * This method allow to store the
     * entity manager to register UseCase
     * into the database.
     *
     * @param EntityManager $entityManager The current entity manager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set form factory.
     *
     * This method store the form factory
     * service.
     *
     * @param UseCaseFormFactory $formFactory The form factory that create the form
     */
    public function setFormFactory(UseCaseFormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

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
        return 'AddUseCaseFormModule';
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
        if (!$objectContainer->hasObject('project') || !$objectContainer->hasObject('request')) {
            return;
        }
        $project = $objectContainer->getObject('project');
        $request = $objectContainer->getObject('request');

        if ($project instanceof Project && $request instanceof Request) {
            $form = $this->formFactory->createForm();

            if (strtoupper($request->getMethod()) === 'POST' && $form instanceof FormInterface) {
                $form->handleRequest($request);
                $data = $form->getData();

                if ($data instanceof UseCase) {
                    $data->setProject($project);

                    $this->entityManager->persist($data);

                    try {
                        $this->entityManager->flush();
                    } catch (UniqueConstraintViolationException $exception) {
                        $form->get('name')->addError(new FormError('This name already exist'));
                    }
                } else {
                    throw new \Exception('Form data retreiving error', 500);
                }
            }

            $twigRequest = new TwigRequest();
            $twigRequest->setTwigPath('CscfaCSManagerUseCaseBundle:module:AddUseCaseFormModule.html.twig')
                ->setArguments(
                    array(
                        'form' => $form->createView(),
                        'projectId' => $project->getId(),
                    )
                );

            return $twigRequest;
        } else {
            return;
        }
    }
}
