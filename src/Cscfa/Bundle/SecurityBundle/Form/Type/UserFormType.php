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
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;

/**
 * UserFormType class.
 *
 * The UserFormType class is the main user entity form
 * builder. It's goal is to protect the logic of User
 * instance by serving a default User form.
 *
 * @category Form
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Role
 */
class UserFormType extends AbstractType
{
    /**
     * The RoleManager.
     *
     * It allow access to specific
     * management method. Specificaly,
     * it used here to provide an
     * array of the Roles name.
     *
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * UserFormType constructor.
     *
     * This allow to store a RoleManager
     * instance to access to specific
     * management methods.
     *
     * @param RoleManager $roleManager The Role manager service
     */
    public function __construct(RoleManager $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    /**
     * Building the user form.
     *
     * This method allow to create a default
     * form for an User instance. This method
     * provide an hidden id field, a username
     * test field, an email field, an enabled
     * checkbox, a salt text field, a password
     * field, a locked checkbox, an expiration
     * date, a role choice, a credential date
     * expiration field.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The form options.
     *
     * @see    \Symfony\Component\Form\AbstractType::buildForm()
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
            ->add(
                'username', 'text', array(
                    'max_length' => '255',
                    'label' => 'core.form.user.label.username',
                    'translation_domain' => 'form'
                    )
            )->add(
                'email', 'email', array(
                    'max_length' => '255',
                    'label' => 'core.form.user.label.email',
                    'translation_domain' => 'form'
                    )
            )->add(
                'enabled', 'checkbox', array(
                    'value' => true,
                    'label' => 'core.form.user.label.enabled',
                    'translation_domain' => 'form'
                    )
            )->add(
                'salt', 'text', array(
                    'label' => 'core.form.user.label.salt',
                    'translation_domain' => 'form'
                    )
            )->add(
                'password', 'password', array(
                    'label' => 'core.form.user.label.password',
                    'translation_domain' => 'form'
                    )
            )->add(
                'locked', 'checkbox', array(
                    'value' => false,
                    'label' => 'core.form.user.label.locked',
                    'translation_domain' => 'form'
                    )
            )->add(
                'expiresAt', 'datetime', array(
                    'label' => 'core.form.user.label.expiresAt',
                    'translation_domain' => 'form'
                    )
            )->add(
                'roles', 'choice', array(
                    'choices' => $this->roleManager->getRolesName(),
                    'label' => 'core.form.user.label.child',
                    'translation_domain' => 'form'
                    )
            )->add(
                'credentialsExpireAt', 'datetime', array(
                    'label' => 'core.form.user.label.credentialsExpireAt',
                    'translation_domain' => 'form'
                    )
            )->add('save', 'submit');
    }

    /**
     * Get the formType name.
     *
     * This method return the current
     * form type name as string.
     *
     * @see    \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return "UserFormType";
    }
}
