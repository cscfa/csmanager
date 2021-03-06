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
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\CoreBundle\BootstrapStepper;

use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper as Base;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * DatagridStepper class
 *
 * This class allow to use a bootstrap
 * display for datagrid.
 *
 * @category Object
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DatagridStepper extends Base
{
    /**
     * DatagridStepper attribute
     * 
     * This attribute register
     * the symfony router
     * service.
     * 
     * @var Router
     */
    protected $router;
    
    /**
     * DatagridStepper attribute
     * 
     * This attribute indicate
     * the route to use to access
     * to specific datagrid element.
     * 
     * @var string
     */
    protected $selectRoute;
    
    /**
     * Set arguments
     * 
     * This method allow the
     * service container to 
     * initialize the class 
     * attributes.
     * 
     * @param Router $router      The router service
     * @param string $selectRoute The route to use on data selection generation
     * 
     * @return void
     */
    public function setArguments(Router $router, $selectRoute = null)
    {
        $this->router = $router;
        $this->selectRoute = $selectRoute;
    }
    
    /**
     * Set callbacks
     * 
     * This method allow the
     * container to initialize
     * the callbacks.
     * 
     * @return void
     */
    public function setCallBacks()
    {
        $this->addCallback("onGrid", function(){
            return "class='table table-striped table-hover'";
        });
        
        if ($this->selectRoute !== null) {
            $this->addCallback("onRow", function($type, $process, $row, $data){
                $class = "class=cs-datagrid-selectable-datagrid";
                $href = "href=".$data['router']->generate($data['route'], array('id'=>$row['primary']->getId()));
                
                return $class." ".$href;
            }, false, array("router"=>$this->router, "route"=>$this->selectRoute));
        }
    }
}