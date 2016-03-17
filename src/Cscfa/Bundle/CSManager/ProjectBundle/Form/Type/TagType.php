<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Form
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * TagType class.
 *
 * The TagType implement
 * tag creating form.
 *
 * @category Form
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class TagType extends AbstractType
{
    /**
     * TagType attribute.
     *
     * This attribute allow to
     * process a translation.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Set arguments.
     *
     * This method allow to inject
     * the TagType arguments.
     *
     * @param Translator $translator The translator service
     */
    public function setArguments(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * BuildForm.
     *
     * This build the common
     * type form
     *
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $domain = 'CscfaCSManagerProjectBundle_form_TagType';

        $builder->add('name', 'text', array(
            'label' => $this->translator->trans('name.label', [], $domain),
            'required' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => $this->translator->trans('name.placeholder', [], $domain),
            ),
        ))->add('description', 'textarea', array(
            'label' => $this->translator->trans('description.label', [], $domain),
            'required' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => $this->translator->trans('description.placeholder', [], $domain),
            ),
        ));
    }

    /**
     * configureOptions.
     *
     * Configure the type options
     *
     * @param OptionsResolver $resolver - the option resolver
     *
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectTag',
            'validation_groups' => function (FormInterface $form) {
                return array(
                    'Default',
                );
            },
            'cascade_validation' => true,
        ));
    }

    /**
     * Get name.
     *
     * Return the type name
     *
     * @see    \Symfony\Component\Form\FormTypeInterface::getName()
     *
     * @return string - the type name
     */
    public function getName()
    {
        return 'projectTag';
    }
}
