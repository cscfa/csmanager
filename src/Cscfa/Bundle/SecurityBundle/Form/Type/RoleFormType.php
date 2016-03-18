<?php
/**
 * This file is a part of CSCFA security project.
 *
 * The security project is a security bundle written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Form
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Cscfa\Bundle\SecurityBundle\Util\Manager\RoleManager;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RoleFormType class.
 *
 * The RoleFormType class is the main role entity form
 * builder. It's goal is to protect the logic of Role
 * instance by serving a default Role form.
 *
 * @category Form
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\SecurityBundle\Entity\Role
 */
class RoleFormType extends AbstractType
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
     * RoleFormType constructor.
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
     * Building the role form.
     *
     * This method allow to build a default
     * form for a Role instance. This form
     * provide an hidden identity field, a
     * name field, a child field and a save
     * field.
     *
     * The name field represent a simple text
     * input limited to 255 characters.
     *
     * The child field represent a select input
     * that provide selection for each existing
     * roles. It use RoleManager.
     *
     * The labels are translates from the 'form'
     * domain of SecurityBundle with core.form.role.label
     * prefix.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The form options.
     *
     * @see    \Symfony\Component\Form\AbstractType::buildForm()
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
            ->add(
                'name',
                'text',
                array(
                'max_length' => '255',
                'label' => 'security.form.role.label.name',
                'translation_domain' => 'form',
                )
            )->add(
                'child',
                'choice',
                array(
                'choices' => $this->roleManager->getRolesName(),
                'label' => 'security.form.role.label.child',
                'translation_domain' => 'form',
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
     *
     * @return string
     */
    public function getName()
    {
        return 'RoleFormType';
    }
}
