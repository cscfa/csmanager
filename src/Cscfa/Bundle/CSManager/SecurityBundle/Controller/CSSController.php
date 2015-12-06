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
namespace Cscfa\Bundle\CSManager\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * CSSController class.
 *
 * The CSSController provide
 * access to the css files of
 * the security bundle.
 *
 * @category Controller
 * @package  CscfaCSManagerSecurityBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CSSController extends Controller
{

    /**
     * Base action.
     *
     * This method return the css 
     * header Connecting celement
     * file of the csmanager
     * security bundle.
     *
     * @return Response
     */
    public function headerConnectingAction($media)
    {
        return $this->getResponse($this->renderView("CscfaCSManagerSecurityBundle:CSS:headerConnecting.css.twig"));
    }

    /**
     * forgot action.
     * 
     * This method return the forgot
     * css file of the csmanager
     * security bundle.
     * 
     * @return Response
     */
    public function forgotAction($media)
    {
        return $this->getResponse($this->renderView("CscfaCSManagerSecurityBundle:CSS:forgot.css.twig"));
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
