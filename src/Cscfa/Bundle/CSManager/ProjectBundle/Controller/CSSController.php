<?php
/**
 * This file is a part of CSCFA csmanager project.
 *
 * The csmanager project is a project manager written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category   Controller
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * CSSController class.
 *
 * The CSSController provide
 * access to the css files of
 * the project bundle.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CSSController extends Controller
{
    const BASE_COLOR = 'd3d3d3';

    /**
     * Base action.
     *
     * This method return the base
     * css file of the csmanager
     * project bundle.
     *
     * @param string $media The media type
     *
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function baseAction($media)
    {
        $twigArray = array('base_color' => self::BASE_COLOR);

        return $this->getResponse($this->renderView('CscfaCSManagerProjectBundle:CSS:base.css.twig', $twigArray));
    }

    /**
     * Get response.
     *
     * This method return a
     * preformated response for
     * matching css type.
     *
     * @param string $content The response content to send
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResponse($content)
    {
        $response = new Response($content);
        $response->setCharset('UTF-8')->setStatusCode(Response::HTTP_OK)->headers->set('Content-Type', 'text/css');

        return $response;
    }
}
