<?php

namespace Wiki\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DiscussionController extends Controller
{
    public function indexAction()
    {
		$discussions = $this->getDoctrine()->getRepository('WikiUserBundle:Discussion')->findAll();
        return $this->render('WikiUserBundle:Discussion:index.html.twig' , array ( 'discussions' => $discussions ) );
    }
}
