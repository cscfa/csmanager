<?php
/**
 * This file is a part of CSCFA toolbox project.
 *
 * The toolbox project is a toolbox written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Reflection
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\ToolboxBundle\Converter\Reflective;

/**
 * ReflectionTool class.
 *
 * The ReflectionTool class is used
 * to extract class informations.
 *
 * @category Reflection
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class ReflectionTool extends \ReflectionClass
{
    /**
     * The settables methods.
     *
     * This parameter represent
     * an array that contain
     * all of the setters method
     * of the current class.
     *
     * @var array
     */
    protected $settables = array();

    /**
     * Constructs a ReflectionClass.
     *
     * The ReflectionTool default constructor.
     *
     * @param class $argument The class to use as base class
     *
     * @link http://www.php.net/manual/en/reflectionclass.construct.php
     */
    public function __construct($argument)
    {
        parent::__construct($argument);

        $this->setSettable();
    }

    /**
     * Set settables.
     *
     * This method allow to register
     * an associative array that represent
     * a parameter and method association
     * with the setting methods of the
     * class.
     */
    protected function setSettable()
    {
        $reflexMethods = $this->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array();

        foreach ($reflexMethods as $method) {
            if (preg_match('/^(set.+)|(add.+)/', $method->getName())) {
                $methods[lcfirst(substr($method->getName(), 3))] = $method->getName();
            }
        }

        $this->settables = $methods;
    }

    /**
     * Get settables.
     *
     * This method allow to get
     * the associative array that represent
     * a parameter and method association
     * with the setting methods of the
     * class.
     *
     * @return array
     */
    public function getSettable()
    {
        return $this->settables;
    }
}
