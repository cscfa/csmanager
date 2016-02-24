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
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * CreateProjectType class.
 *
 * The CreateProjectType implement
 * project creating form.
 *
 * @category Form
 * @package  CscfaCSManagerProjectBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CreateProjectType extends AbstractType
{
    /**
     * CreateProjectType attribute
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
     * the CreateProjectType arguments.
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
        $domain = "CscfaCSManagerProjectBundle_form_CreateProjectType";
        
        $builder->add("name", "text", array(
            'label' => $this->translator->trans("name.label", [], $domain),
            'required' => true,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => $this->translator->trans("name.placeholder", [], $domain)
            )
        ))->add("summary", "textarea", array(
            'label' => $this->translator->trans("summary.label", [], $domain),
            'required' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => $this->translator->trans("summary.placeholder", [], $domain)
            )
        ))->add("status", "entity", array(
            "class"=>"Cscfa\Bundle\CSManager\ProjectBundle\Entity\ProjectStatus",
            "label"=>$this->translator->trans("status.choice_label", [], $domain),
            "choice_label"=>"name",
            "placeholder"=>$this->translator->trans("status.placeholder", [], $domain),
            'attr' => array(
                'class' => 'form-control'
            )
        ))->add("reset", "reset", array(
            "label"=>$this->translator->trans("reset.label", [], $domain),
            "attr"=>array(
                "class"=>"btn btn-info"
            )
        ))->add("submit", "submit", array(
            "label"=>$this->translator->trans("submit.label", [], $domain),
            "attr"=>array(
                "class"=>"btn btn-success"
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
            'data_class' => 'Cscfa\Bundle\CSManager\ProjectBundle\Entity\Project',
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
        return "createProject";
    }
}