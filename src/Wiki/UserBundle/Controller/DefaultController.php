<?php

namespace Wiki\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WikiUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
