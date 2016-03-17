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
     * core bundle.
     *
     * @param string $media The media to process
     *
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function baseAction($media)
    {
        $twigArray = array('base_color' => self::BASE_COLOR);

        return $this->getResponse($this->renderView('CscfaCSManagerCoreBundle:CSS:base.css.twig', $twigArray));
    }

    /**
     * Header action.
     *
     * This method return the header
     * css file of the csmanager
     * core bundle.
     *
     * @param string $media The media to process
     *
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function headerAction($media)
    {
        $twigArray = array('base_color' => self::BASE_COLOR);

        return $this->getResponse($this->renderView('CscfaCSManagerCoreBundle:CSS:header.css.twig', $twigArray));
    }

    /**
     * Tool action.
     *
     * This method return the tool
     * css file of the csmanager
     * core bundle.
     *
     * @param string $media The media to process
     *
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toolAction($media)
    {
        $twigArray = array(
            'base_color' => self::BASE_COLOR,
            'fieldset_background' => array(0, 0, 0, 0.02),
            'fieldset_border' => array(0, 0, 0, 0.1),
            'fieldset_shadow' => 'grey',
        );

        return $this->getResponse($this->renderView('CscfaCSManagerCoreBundle:CSS:tool.css.twig', $twigArray));
    }

    /**
     * Index action.
     *
     * This method return the index
     * css file of the csmanager
     * core bundle.
     *
     * @param string $media The media to process
     *
     * @return Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction($media)
    {
        $twigArray = array('base_color' => self::BASE_COLOR);

        return $this->getResponse($this->renderView('CscfaCSManagerCoreBundle:CSS:index.css.twig', $twigArray));
    }

    /**
     * Get response.
     *
     * This method return a
     * preformated response for
     * matching css type.
     *
     * @param string $content The content to send
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
