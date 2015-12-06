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
use Symfony\Component\HttpFoundation\Response;

/**
 * CSSController class.
 *
 * The CSSController provide
 * access to the css files of
 * the core bundle.
 *
 * @category Controller
 * @package  CscfaCSManagerCoreBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CSSController extends Controller
{
    const BASE_COLOR = "#d3d3d3";

    /**
     * Base action.
     *
     * This method return the base
     * css file of the csmanager
     * core bundle.
     *
     * @return Response
     */
    public function baseAction($media)
    {
        $ar = array("base_color"=>self::BASE_COLOR);
        
        return $this->getResponse($this->renderView("CscfaCSManagerCoreBundle:CSS:base.css.twig", $ar));
    }

    /**
     * header action.
     * 
     * This method return the header
     * css file of the csmanager
     * core bundle.
     * 
     * @return Response
     */
    public function headerAction($media)
    {
        $ar = array("base_color"=>self::BASE_COLOR);
        
        return $this->getResponse($this->renderView("CscfaCSManagerCoreBundle:CSS:header.css.twig", $ar));
    }

    /**
     * tool action.
     * 
     * This method return the tool
     * css file of the csmanager
     * core bundle.
     * 
     * @return Response
     */
    public function toolAction($media)
    {
        $ar = array("base_color"=>self::BASE_COLOR);
        
        return $this->getResponse($this->renderView("CscfaCSManagerCoreBundle:CSS:tool.css.twig", $ar));
    }

    /**
     * Index action.
     *
     * This method return the index
     * css file of the csmanager
     * core bundle.
     *
     * @return Response
     */
    public function indexAction($media)
    {
        $ar = array("base_color"=>self::BASE_COLOR);
        
        return $this->getResponse($this->renderView("CscfaCSManagerCoreBundle:CSS:index.css.twig", $ar));
    }

    /**
     * Get response.
     * 
     * This method return a
     * preformated response for
     * matching css type.
     * 
     * @param string $content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResponse($content)
    {
        $response = new Response($content);
        $response->setCharset("UTF-8")->setStatusCode(Response::HTTP_OK)->headers->set("Content-Type", "text/css");
        return $response;
    }
}
