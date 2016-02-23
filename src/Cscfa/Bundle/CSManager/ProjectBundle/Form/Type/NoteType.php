<?php
namespace Cscfa\Bundle\CSManager\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

class NoteType extends AbstractType
{
    /**
     * NoteType attribute
     * 
     * This attribute allow to
     * process a translation.
     * 
     * @var Translator
     */
    protected $translator;
    
    /**
     * Set arguments
     * 
     * This method allow to inject
     * the NoteType arguments.
     * 
     * @param Translator $translator The translator service
     */
    public function setArguments(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * BuildForm
     * 
     * This build the common
     * type form
     * 
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     * 
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $domain = "CscfaCSManagerProjectBundle_form_NoteType";
        
        $builder->add("content", "textarea", array(
            'label' => $this->translator->trans("content.label", [], $domain),
            'required' => true,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => $this->translator->trans("content.placeholder", [], $domain)
            )
        ));
    }

    /**
     * configureOptions
     * 
     * Configure the type options
     * 
     * @param OptionsResolver $resolver - the option resolver
     * 
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectNote',
            'validation_groups' => function (FormInterface $form)
            {
                // $data = $form->getData();
                return array(
                    "Default"
                );
            },
            'cascade_validation' => true
        ));
    }

    /**
     * Get name
     * 
     * Return the type name
     * 
     * @see    \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string - the type name
     */
    public function getName()
    {
        return "projectNote";
    }
}