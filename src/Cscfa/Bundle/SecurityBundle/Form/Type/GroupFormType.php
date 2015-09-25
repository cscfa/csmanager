<?php
/**
 * This file is a part of CSCFA security project.
 * 
 * The security project is a security bundle written in php
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
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * GroupFormType class.
 *
 * The GroupFormType class is the main group entity form
 * builder. It's goal is to protect the logic of Group
 * instance by serving a default Group form.
 *
 * @category Form
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Role
 */
class GroupFormType extends AbstractType
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
     * GroupFormType constructor.
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
     * Building the group form.
     *
     * This method allow to build a default
     * form for a Group instance. This form
     * provide an hidden identity field, a
     * name field, a locked field, an expiresAt
     * field, a roles field and a save field.
     *
     * The name field represent a simple text
     * input limited to 255 characters.
     *
     * The locked field represent a simple 
     * checkox input.
     *
     * The expiresAt field represent a datetime
     * input.
     *
     * The roles field represent a select input
     * that provide selection for each existing
     * roles. It use RoleManager.
     *
     * The labels are translates from the 'form'
     * domain of SecurityBundle with core.form.group.label
     * prefix.
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
                'name', 'text', array(
                'max_length' => '255',
                'label' => 'security.form.group.label.name',
                'translation_domain' => 'form'
                )
            )->add(
                'locked', 'checkbox', array(
                    'value' => false,
                    'label' => 'security.form.group.label.locked',
                    'translation_domain' => 'form'
                    )
            )->add(
                'expiresAt', 'datetime', array(
                    'label' => 'security.form.group.label.expiresAt',
                    'translation_domain' => 'form'
                    )
            )->add(
                'roles', 'choice', array(
                    'choices' => $this->roleManager->getRolesName(),
                    'label' => 'security.form.group.label.child',
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
        return 'GroupFormType';
    }
}
