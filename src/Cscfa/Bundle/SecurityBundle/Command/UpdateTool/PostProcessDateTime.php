<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\SecurityBundle\Command\UpdateTool;

use Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PostProcessEventInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandColorFacade;
use Cscfa\Bundle\ToolboxBundle\BaseInterface\Error\ErrorRegisteryInterface;
use Cscfa\Bundle\ToolboxBundle\Facade\Command\CommandFacade;

/**
 * PostProcessDateTime class.
 *
 * The PostProcessDateTime class purpose feater to
 * post process a date time format.
 *
 * @category Command
 * @package  CscfaSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PostProcessDateTime implements PostProcessEventInterface
{
    /**
     * The format.
     * 
     * This represent the
     * format to use with
     * the DateTime instance.
     * 
     * @var string
     */
    protected $format;
    
    /**
     * Default constructor.
     * 
     * This constructor allow
     * to specify the DateTime
     * format. Note this format
     * is set as 'Y-m-d H:i:s'
     * as default.
     * 
     * @param string $format The DateTime format
     */
    public function __construct($format = "Y-m-d H:i:s")
    {
        $this->format = $format;
    }

    /**
     * The post process method.
     *
     * This method parse a string
     * into a new DateTime instance.
     *
     * @param mixed                   $result        The result value
     * @param ErrorRegisteryInterface &$to           A builder that is used to apply the value
     * @param array                   &$param        The current param array
     * @param CommandFacade           $commandFacade The current CommandFacade instance
     * @param CommandColorFacade      $commandColor  A CommandColorFacade given by the command facade
     * 
     * @see    \Cscfa\Bundle\ToolboxBundle\BaseInterface\Event\PostProcessEvent::postProcess()
     * @return \DateTime
     */
    public function postProcess($result, ErrorRegisteryInterface &$to, array &$param, CommandFacade $commandFacade, CommandColorFacade $commandColor) 
    {
        if (is_string($result)) {
            $result = \DateTime::createFromFormat($this->format, $result);
        
            if (! ($result instanceof \DateTime)) {
                $commandColor->clear();
                $commandColor->addText("\n");
                $commandColor->addText($param["failure"], "failure");
                $commandColor->addText("\n");
                $commandColor->write();
        
                $param["active"] = false;
            }
        }
        return $result;
    }

}
