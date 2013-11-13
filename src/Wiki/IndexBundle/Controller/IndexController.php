<?php

namespace Wiki\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wiki\IndexBundle\Entity\Tag;

class IndexController extends Controller
{
	public function homeAction()
	{
		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('WikiIndexBundle:Article')->findBy(array() , array('create' => 'DESC'));
		$tags = $em->getRepository('WikiIndexBundle:Tag')->findBy(array() , array('name' => 'ASC'));

		return $this->render('WikiIndexBundle:Index:home.html.twig' , array( 'articles' => $articles,
																			 'tags' => $tags ));
	}
	public function articlesByTagAction(Tag $tag = null)
	{
		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('WikiIndexBundle:Article')->articlesByTag($tag);

		return $this->render('WikiIndexBundle:Index:articles.html.twig' , array( 'articles' => $articles ));
	}
	public function searchAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$search = $request->query->get('s');
			return $this->render('WikiIndexBundle:Index:search.html.twig' , array ( 'search' => $search ));
	}
}
