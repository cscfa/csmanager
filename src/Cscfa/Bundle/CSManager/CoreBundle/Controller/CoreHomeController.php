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
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\CSManager\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cscfa\Bundle\CSManager\SecurityBundle\Objects\singin\SignInObject;
use Symfony\Component\HttpFoundation\Response;

/**
 * CoreHomeController class.
 *
 * The CoreHomeController implement
 * access method to home core system.
 *
 * @category Controller
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class CoreHomeController extends Controller
{
    /**
     * Core index action.
     *
     * This action provide logic
     * for the index page.
     *
     * @Template
     */
    public function indexAction()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $authenticationUtils = $this->get('security.authentication_utils');
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            $signin = new SignInObject();
            $signinForm = $this->createForm('signin', $signin, array('action' => $this->generateUrl('register')));

            return array(
                'last_username' => $lastUsername,
                'error' => $error,
                'signinForm' => $signinForm->createView(),
            );
        } else {
            return array();
        }
    }

    /**
     * Authenticated action.
     *
     * This action return a json
     * that grant the authenticated
     * state of the session.
     *
     * @return Response
     */
    public function authenticatedAction()
    {
        $response = new Response(json_encode($this->isGranted('IS_AUTHENTICATED_FULLY')), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
