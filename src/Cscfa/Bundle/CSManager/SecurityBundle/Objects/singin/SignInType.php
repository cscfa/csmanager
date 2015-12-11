<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * SignInType class.
 *
 * The SignInType store
 * the base signin form.
 *
 * @category Object
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class SignInType extends AbstractType
{
    /**
     * The entity manager service
     * 
     * This variable is used to
     * manage the database access
     * 
     * @var EntityManager
     */
    protected $manager;
    
    /**
     * SignInType constructor
     * 
     * This register the entity
     * manager service
     * 
     * @param EntityManager $manager - the entity manager service
     */
    public function __construct(EntityManager $manager){
        $this->manager = $manager;
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
    public function buildForm(FormBuilderInterface $builder, array $options){
        $this->buildBase($builder, $options);
        $this->buildPhone($builder, $options);
        $this->buildYourSelf($builder, $options);
        $this->buildCompany($builder, $options);
        $this->buildAddress($builder, $options);
        
        $builder->add("register", "submit", array('label'=>'register', 'attr'=>array('class'=>'btn btn-success')));
        
        if(array_key_exists("action", $options)){
            $builder->setAction($options['action']);
        }
    }
    
    /**
     * BuildAddress
     * 
     * This build the address section
     * of the common form
     * 
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     */
    public function buildAddress(FormBuilderInterface $builder, array $options){

        // REFERER
        $builder->add(
            "referer",
            "text",
            array(
                'label'=>'Receiver name : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'referer name'
                )
            )
        );

        // ADDRESS
        $builder->add(
            "adress",
            "text",
            array(
                'label'=>'Address : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'XX address street'
                )
            )
        );

        // ADDRESS COMPLEMENT
        $builder->add(
            "complement",
            "text",
            array(
                'label'=>'Address complement : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'address complement'
                )
            )
        );

        // ADDRESS TOWN
        $builder->add(
            "town",
            "text",
            array(
                'label'=>'Address town : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'address town name'
                )
            )
        );

        // ADDRESS POSTAL CODE
        $builder->add(
            "postalCode",
            "number",
            array(
                'label'=>'Address postal code : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'postal code'
                )
            )
        );

        // ADDRESS COUNTRY 
        $builder->add(
            "country",
            "text",
            array(
                'label'=>'Address country : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'address country'
                )
            )
        );
    }
    
    /**
     * BuildCompany
     * 
     * This build the company section
     * of the common form
     * 
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     */
    public function buildCompany(FormBuilderInterface $builder, array $options){

        // COMPANY
        $builder->add(
            "company",
            "text",
            array(
                'label'=>'Company : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company name'
                )
            )
        );

        // COMPANY SERVICE 
        $builder->add(
            "service",
            "text",
            array(
                'label'=>'Company service : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r job service'
                )
            )
        );

        // COMPANY JOB 
        $builder->add(
            "job",
            "text",
            array(
                'label'=>'You\'r job : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r job name'
                )
            )
        );

        // COMPANY REFERER
        $builder->add(
            "companyReferer",
            "text",
            array(
                'label'=>'Company receiver name : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company address referer'
                )
            )
        );

        // COMPANY ADDRESS
        $builder->add(
            "companyAdress",
            "text",
            array(
                'label'=>'Company address : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'XX you\'r company address street'
                )
            )
        );

        // COMPANY ADDRESS COMPLEMENT
        $builder->add(
            "companyComplement",
            "text",
            array(
                'label'=>'Company address complement : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company address complement'
                )
            )
        );

        // COMPANY ADDRESS TOWN
        $builder->add(
            "companyTown",
            "text",
            array(
                'label'=>'Company address town : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company town'
                )
            )
        );

        // COMPANY ADDRESS POSTAL CODE
        $builder->add(
            "companyPostalCode",
            "number",
            array(
                'label'=>'Company address postal code : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company postal code'
                )
            )
        );

        // COMPANY ADDRESS COUNTRY 
        $builder->add(
            "companyCountry",
            "text",
            array(
                'label'=>'Company address country : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'you\'r company country'
                )
            )
        );
    }

    /**
     * buildYourSelf
     *
     * This build the 'yourself' section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     */
    public function buildYourSelf(FormBuilderInterface $builder, array $options){


        // FIRSTNAME
        $builder->add(
            "firstName",
            "text",
            array(
                'label'=>'First name : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'first name'
                )
            )
        );

        // LASTNAME
        $builder->add(
            "lastName",
            "text",
            array(
                'label'=>'Last name : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'last name'
                )
            )
        );

        // SEX
        $builder->add(
            "sex",
            "choice",
            array(
                'label'=>'Gender : ',
                'choices'=>array(1=>"Male", 0=>"Female"),
                'attr'=>array(
                    'class'=>'form-control'
                )
            )
        );
        
        // BIRTHDAY
        $currentDate = new \DateTime();
        $year = intval($currentDate->format("Y"));
        $years = array();
        for($i = 0; $i < 150;$i ++){
            $years[] = $year;
            $year --;
        }
        
        $builder->add(
            "birthday",
            "date",
            array(
                'label'=>'Bith date : ',
                'widget'=>'choice',
                'years'=>$years,
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control'
                )
            )
        );

        // BIOGRAPHY
        $builder->add(
            "biography",
            "textarea",
            array(
                'label'=>'Biogaphy : ',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control'
                )
            )
        );
    }

    /**
     * buildPhone
     *
     * This build the phone section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     */
    public function buildPhone(FormBuilderInterface $builder, array $options)
    {
        // TYPE
        $builder->add(
                "phoneType", 
                "entity",
                array(
                    'label'=>'Phone type : ',
                    'class'=>'Cscfa\Bundle\CSManager\UserBundle\Entity\Type',
                    'choice_label'=>'label',
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                )
            );

        // NUMBER
        $builder->add(
            "phoneNumber",
            "text",
            array(
                'label'=>'Number : ',
                    'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'Phone number'
                )
            )
        );
    }

    /**
     * buildBase
     *
     * This build the base section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     * @param array                $options - the form options
     */
    public function buildBase(FormBuilderInterface $builder, array $options)
    {
        // PSEUDO
        $builder->add(
                "pseudo", 
                "text",
                array(
                    'label'=>'Pseudo : ',
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder'=>'pseudo'
                    )
                )
            );

        // EMAIL
        $builder->add(
            "email",
            "repeated",
            array(
                'type'=>'email',
                'first_name' => 'email',
                'second_name' => 'email_confirm',
                'first_options'  => array(
                    'label' => 'Email : ',
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder'=>'email'
                    )
                ),
                'second_options' => array(
                    'label' => 'Email confirmation : ',
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder'=>'rewrite'
                    )
                ), 
                
            )
        );

        // PASSWORD
        $builder->add(
            "password",
            "repeated",
            array(
                'type'=>'password',
                'first_name' => 'password',
                'second_name' => 'password_confirm',
                'first_options'  => array(
                    'label' => 'Password : ',
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder'=>'password'
                    )
                ),
                'second_options' => array(
                    'label' => 'Password confirmation : ',
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder'=>'rewrite'
                    )
                ), 
                
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
            'data_class' => 'Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin\SignInObject',
            'validation_groups' =>function (FormInterface $form){
                $data = $form->getData();
                
                if ($data instanceof SignInObject) {
                    return $data->getGroups();
                }else{
                    return array("Default");
                }
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
    public function getName(){
        return "signin";
    }
}
