<?php

namespace Cscfa\Bundle\CSManager\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CscfaCSManagerDashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
