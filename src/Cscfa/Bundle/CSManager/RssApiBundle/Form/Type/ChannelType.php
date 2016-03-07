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
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.
 */
namespace Cscfa\Bundle\CSManager\RssApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;

/**
 * ChannelType class.
 *
 * The ChannelType implement
 * channel creating form.
 *
 * @category Form
 * @package  CscfaCSManagerRssApiBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class ChannelType extends AbstractType {
    /**
     * ChannelType attribute
     * 
     * This attribute allow to
     * process a translation.
     * 
     * @var TranslatorInterface
     */
    protected $translator;
    
    /**
     * Set arguments
     * 
     * This method allow to inject
     * the ChannelType arguments.
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
        $domain = "CscfaCSManagerRssApiBundle_form_ChannelType";
        $id = $options["data"]->getUser()->getId();
        
        $builder->add(
                "name",
                "text",
                array(
                    "label"=>$this->translator->trans("name.label", [], $domain),
                    "attr"=>array(
                        "class"=>"form-control"
                    )
                )
            )->add(
                "description", 
                "textarea",
                array(
                    "label"=>$this->translator->trans("description.label", [], $domain),
                    "attr"=>array(
                        "class"=>"form-control"
                    )
                )
            )->add(
                "item",
                "choice",
                array(
                    "choices"=>array(
                        "project.event.created"=>$this->translator->trans("item.project.created", [], $domain),
                        "project.event.addOwner"=>$this->translator->trans("item.project.addOwner", [], $domain),
                        "project.event.removed"=>$this->translator->trans("item.project.removed", [], $domain),
                        "project.event.addNote"=>$this->translator->trans("item.project.addNote", [], $domain)
                    ),
                    "mapped"=>false,
                    "expanded"=>true,
                    "multiple"=>true,
                    "label"=>$this->translator->trans("item.label", [], $domain),
                    "required"=>false
                )
            )->add(
                "user",
                "hidden",
                array(
                    "data"=>$id,
                    "mapped"=>false
                )
            )->add(
                "submit",
                "submit",
                array(
                    "label"=>$this->translator->trans("submit.label", [], $domain),
                    "attr"=>array(
                        "class"=>"btn btn-success"
                    )
                )
            );
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
            'data_class' => 'Cscfa\Bundle\CSManager\RssApiBundle\Entity\Channel',
            'validation_groups' => function (FormInterface $form)
            {
                return array(
                    "Default"
                );
            }
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
        return "rssChannel";
    }
}