<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category   Exception
 * @package    CscfaCSManagerCoreBundle
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link       http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Exception;

use Cscfa\Bundle\CSManager\CoreBundle\Entity\Role;

/**
 * CircularReferenceException class.
 *
 * The CircularReferenceException class represent
 * an error into the Role logic. It used if a
 * Role child create a circular reference with
 * one of his own childs.
 *
 * Notable usage : RoleManager.
 *
 * @category Exception
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Entity\Role
 * @see      Cscfa\Bundle\CSManager\CoreBundle\Util\Manager\RoleManager
 */
class CircularReferenceException extends \Exception
{

    /**
     * CircularReferenceException constructor.
     *
     * This constructor directly create the
     * exception message with the given Role
     * instance informations.
     *
     * The default status code is
     *
     * @param Role $role            
     */
    public function __construct(Role $role)
    {
        parent::__construct("Circular reference into " . $role->getName(), 500, null);
    }
}