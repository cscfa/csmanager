<?php

namespace Cscfa\Bundle\CSManager\ConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CscfaCSManagerConfigBundle:Default:index.html.twig', array('name' => $name));
    }
}
