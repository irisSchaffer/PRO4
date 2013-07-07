<?php

namespace PRO4\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PRO4FileBundle:Default:index.html.twig', array('name' => $name));
    }
}