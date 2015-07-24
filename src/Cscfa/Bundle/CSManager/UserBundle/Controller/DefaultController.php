<?php

namespace Cscfa\Bundle\CSManager\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CscfaCSManagerUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
