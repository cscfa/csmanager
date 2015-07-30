<?php

namespace Cscfa\Bundle\CSManager\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CscfaCSManagerTaskBundle:Default:index.html.twig', array('name' => $name));
    }
}
