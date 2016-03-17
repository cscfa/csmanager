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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin\SignInObject;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * SignInType class.
 *
 * The SignInType store
 * the base signin form.
 *
 * @category Object
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class SignInType extends AbstractType
{
    /**
     * The entity manager service.
     *
     * This variable is used to
     * manage the database access
     *
     * @var EntityManager
     */
    protected $manager;

    /**
     * SignInType attribute.
     *
     * This attribute allow to
     * process a translation.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * SignInType attribute.
     *
     * This attribute indicate
     * the translation domain.
     *
     * @var string
     */
    protected $domain = 'CscfaCSManagerSecurityBundle_form_SigninType';

    /**
     * SignInType constructor.
     *
     * This register the entity
     * manager service
     *
     * @param EntityManager $manager    - the entity manager service
     * @param Translator    $translator - The translator service
     */
    public function __construct(EntityManager $manager, TranslatorInterface $translator)
    {
        $this->manager = $manager;
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
        $this->buildBase($builder);
        $this->buildPhone($builder);
        $this->buildYourSelf($builder);
        $this->buildCompany($builder);
        $this->buildAddress($builder);

        $builder->add(
            'register',
            'submit',
            array(
                'label' => $this->translator->trans('form.register.label', [], $this->domain),
                'attr' => array('class' => 'btn btn-success'),
            )
        );

        if (array_key_exists('action', $options)) {
            $builder->setAction($options['action']);
        }
    }

    /**
     * BuildAddress.
     *
     * This build the address section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     */
    public function buildAddress(FormBuilderInterface $builder)
    {

        // REFERER
        $builder->add(
            'referer',
            'text',
            array(
                'label' => $this->translator->trans('address.referer.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.referer.placeholder', [], $this->domain),
                ),
            )
        );

        // ADDRESS
        $builder->add(
            'adress',
            'text',
            array(
                'label' => $this->translator->trans('address.address.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.address.placeholder', [], $this->domain),
                ),
            )
        );

        // ADDRESS COMPLEMENT
        $builder->add(
            'complement',
            'text',
            array(
                'label' => $this->translator->trans('address.complement.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.complement.placeholder', [], $this->domain),
                ),
            )
        );

        // ADDRESS TOWN
        $builder->add(
            'town',
            'text',
            array(
                'label' => $this->translator->trans('address.town.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.town.placeholder', [], $this->domain),
                ),
            )
        );

        // ADDRESS POSTAL CODE
        $builder->add(
            'postalCode',
            'number',
            array(
                'label' => $this->translator->trans('address.postalCode.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.postalCode.placeholder', [], $this->domain),
                ),
            )
        );

        // ADDRESS COUNTRY
        $builder->add(
            'country',
            'text',
            array(
                'label' => $this->translator->trans('address.country.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('address.country.placeholder', [], $this->domain),
                ),
            )
        );
    }

    /**
     * BuildCompany.
     *
     * This build the company section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     */
    public function buildCompany(FormBuilderInterface $builder)
    {

        // COMPANY
        $builder->add(
            'company',
            'text',
            array(
                'label' => $this->translator->trans('company.company.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.company.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY SERVICE
        $builder->add(
            'service',
            'text',
            array(
                'label' => $this->translator->trans('company.service.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.service.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY JOB
        $builder->add(
            'job',
            'text',
            array(
                'label' => $this->translator->trans('company.job.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.job.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY REFERER
        $builder->add(
            'companyReferer',
            'text',
            array(
                'label' => $this->translator->trans('company.companyReferer.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.companyReferer.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY ADDRESS
        $builder->add(
            'companyAdress',
            'text',
            array(
                'label' => $this->translator->trans('company.companyAdress.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.companyAdress.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY ADDRESS COMPLEMENT
        $builder->add(
            'companyComplement',
            'text',
            array(
                'label' => $this->translator->trans('company.companyComplement.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans(
                        'company.companyComplement.placeholder',
                        [],
                        $this->domain
                    ),
                ),
            )
        );

        // COMPANY ADDRESS TOWN
        $builder->add(
            'companyTown',
            'text',
            array(
                'label' => $this->translator->trans('company.companyTown.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.companyTown.placeholder', [], $this->domain),
                ),
            )
        );

        // COMPANY ADDRESS POSTAL CODE
        $builder->add(
            'companyPostalCode',
            'number',
            array(
                'label' => $this->translator->trans('company.companyPostalCode.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans(
                        'company.companyPostalCode.placeholder',
                        [],
                        $this->domain
                    ),
                ),
            )
        );

        // COMPANY ADDRESS COUNTRY
        $builder->add(
            'companyCountry',
            'text',
            array(
                'label' => $this->translator->trans('company.companyCountry.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('company.companyCountry.placeholder', [], $this->domain),
                ),
            )
        );
    }

    /**
     * buildYourSelf.
     *
     * This build the 'yourself' section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     */
    public function buildYourSelf(FormBuilderInterface $builder)
    {

        // FIRSTNAME
        $builder->add(
            'firstName',
            'text',
            array(
                'label' => $this->translator->trans('yourself.firstName.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('yourself.firstName.placeholder', [], $this->domain),
                ),
            )
        );

        // LASTNAME
        $builder->add(
            'lastName',
            'text',
            array(
                'label' => $this->translator->trans('yourself.lastName.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('yourself.lastName.placeholder', [], $this->domain),
                ),
            )
        );

        // SEX
        $builder->add(
            'sex',
            'choice',
            array(
                'label' => $this->translator->trans('yourself.sex.label', [], $this->domain),
                'choices' => array(1 => 'Male', 0 => 'Female'),
                'attr' => array(
                    'class' => 'form-control',
                ),
            )
        );

        // BIRTHDAY
        $currentDate = new \DateTime();
        $year = intval($currentDate->format('Y'));
        $years = array();
        for ($i = 0; $i < 150; ++$i) {
            $years[] = $year;
            --$year;
        }

        $builder->add(
            'birthday',
            'date',
            array(
                'label' => $this->translator->trans('yourself.birthday.label', [], $this->domain),
                'widget' => 'choice',
                'years' => $years,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            )
        );

        // BIOGRAPHY
        $builder->add(
            'biography',
            'textarea',
            array(
                'label' => $this->translator->trans('yourself.biography.label', [], $this->domain),
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            )
        );
    }

    /**
     * buildPhone.
     *
     * This build the phone section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     */
    public function buildPhone(FormBuilderInterface $builder)
    {
        // TYPE
        $builder->add(
            'phoneType',
            'entity',
            array(
                    'label' => $this->translator->trans('phone.phoneType.label', [], $this->domain),
                    'class' => 'Cscfa\Bundle\CSManager\UserBundle\Entity\Type',
                    'choice_label' => 'label',
                    'attr' => array(
                        'class' => 'form-control',
                    ),
                )
        );

        // NUMBER
        $builder->add(
            'phoneNumber',
            'text',
            array(
                'label' => $this->translator->trans('phone.phoneNumber.label', [], $this->domain),
                    'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('phone.phoneNumber.placeholder', [], $this->domain),
                ),
            )
        );
    }

    /**
     * buildBase.
     *
     * This build the base section
     * of the common form
     *
     * @param FormBuilderInterface $builder - the form builder
     */
    public function buildBase(FormBuilderInterface $builder)
    {
        // PSEUDO
        $builder->add(
            'pseudo',
            'text',
            array(
                    'label' => $this->translator->trans('base.pseudo.label', [], $this->domain),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('base.pseudo.placeholder', [], $this->domain),
                    ),
                )
        );

        // EMAIL
        $builder->add(
            'email',
            'repeated',
            array(
                'type' => 'email',
                'first_name' => 'email',
                'second_name' => 'email_confirm',
                'first_options' => array(
                    'label' => $this->translator->trans('base.email.first.label', [], $this->domain),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('base.email.first.placeholder', [], $this->domain),
                    ),
                ),
                'second_options' => array(
                    'label' => $this->translator->trans('base.email.second.label', [], $this->domain),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('base.email.second.placeholder', [], $this->domain),
                    ),
                ),

            )
        );

        // PASSWORD
        $builder->add(
            'password',
            'repeated',
            array(
                'type' => 'password',
                'first_name' => 'password',
                'second_name' => 'password_confirm',
                'first_options' => array(
                    'label' => $this->translator->trans('base.password.first.label', [], $this->domain),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('base.password.first.placeholder', [], $this->domain),
                    ),
                ),
                'second_options' => array(
                    'label' => $this->translator->trans('base.password.second.label', [], $this->domain),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans(
                            'base.password.second.placeholder',
                            [],
                            $this->domain
                        ),
                    ),
                ),

            )
        );
    }

    /**
     * configureOptions.
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
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if ($data instanceof SignInObject) {
                    return $data->getGroups();
                } else {
                    return array('Default');
                }
            },
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
        return 'signin';
    }
}
