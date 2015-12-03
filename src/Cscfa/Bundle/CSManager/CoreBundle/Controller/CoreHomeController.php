<?php
/**
 * This file is a part of CSCFA csmanager project.
 * 
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cscfa\Bundle\ToolboxBundle\Tool\Cache\CacheTool;
use Cscfa\Bundle\TwigUIBundle\Element\Advanced\Widget\PopupWidget;
use Cscfa\Bundle\TwigUIBundle\Element\Base\TextTag;
use Cscfa\Bundle\TwigUIBundle\Element\Processor\Style\StyleProcessor;
use Cscfa\Bundle\TwigUIBundle\Element\Processor\Script\ScriptProcessor;

/**
 * CoreHomeController class.
 *
 * The CoreHomeController implement
 * access method to home core system.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CoreHomeController extends Controller
{
    /**
     * @Template
     */
    public function indexAction()
    {
        return array();
    }
}
