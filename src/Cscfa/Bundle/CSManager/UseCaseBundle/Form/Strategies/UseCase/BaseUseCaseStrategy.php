<?php
/**
 * This file is a part of CSCFA UseCase project.
 *
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category FormStrategy
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\UseCase;

use Cscfa\Bundle\CSManager\UseCaseBundle\Form\Strategies\Abstracts\AbstractUseCaseStrategy;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * BaseUseCaseStrategy class.
 *
 * The BaseUseCaseStrategy class
 * perform the building of form for
 * base UseCase creation form.
 *
 * @category FormStrategy
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class BaseUseCaseStrategy extends AbstractUseCaseStrategy
{
    /**
     * Building the form.
     *
     * This method perform the
     * form type building.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The building options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'status',
            'entity',
            array(
                'class' => 'CscfaCSManagerUseCaseBundle:UseCaseStatus',
                'choice_label' => 'name',
                'placeholder' => $this->translator->trans('status.placeholder', [], $this->transDomain),
                'label' => $this->translator->trans('status.label', [], $this->transDomain),
                'attr' => array(
                    'class' => 'form-control',
                ),
            )
        )->add(
            'name',
            'text',
            array(
                'label' => $this->translator->trans('name.label', [], $this->transDomain),
                'attr' => array(
                    'placeholder' => $this->translator->trans('name.placeholder', [], $this->transDomain),
                    'class' => 'form-control',
                ),
            )
        )->add(
            'description',
            'textarea',
            array(
                'label' => $this->translator->trans('description.label', [], $this->transDomain),
                'attr' => array(
                    'placeholder' => $this->translator->trans('description.placeholder', [], $this->transDomain),
                    'class' => 'form-control',
                ),
            )
        );
    }
}
